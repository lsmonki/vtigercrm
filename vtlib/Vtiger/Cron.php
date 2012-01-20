<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************** */

include_once 'vtlib/Vtiger/Utils.php';

/**
 * Provides API to work with Cron tasks
 * @package vtlib
 */
class Vtiger_Cron {

	protected static $schemaInitialized = false;
	protected static $instanceCache = array();
	static $STATUS_DISABLED = 0;
	static $STATUS_ENABLED = 1;
	static $STATUS_RUNNING = 2;
	protected $data;
	protected $bulkMode = false;

	/**
	 * Constructor
	 */
	protected function __construct($values) {
		$this->data = $values;
		self::$instanceCache[$this->getName()] = $this;
	}

	/**
	 * Get id reference of this instance.
	 */
	function getId() {
		return $this->data['id'];
	}

	/**
	 * Get name of this task instance.
	 */
	function getName() {
		return decode_html($this->data['name']);
	}

	/**
	 * Get the frequency set.
	 */
	function getFrequency() {
		return intval($this->data['frequency']);
	}

	/**
	 * Get the timestamp lastrun started.
	 */
	function getLastStart() {
		return intval($this->data['laststart']);
	}

	/**
	 * Get the timestamp lastrun ended.
	 */
	function getLastEnd() {
		return intval($this->data['lastend']);
	}

	/**
	 * Get the user datetimefeild
	 */
	function getLastEndDateTime() {
		if($this->data['lastend'] != null) {
			$lastEndTimeDate = new DateTimeField(date('Y-m-d H:i:s', $this->data['lastend']));
			return $lastEndTimeDate->getDisplayDateTimeValue();
		} else {
			return '';
		}
	}

	/**
	 * Get Time taken to complete task
	 */
	function getTimeDiff() {
		$lastStart = $this->getLastStart();
		$lastEnd = $this->getLastEnd();
		$timeDiff = $lastEnd - $lastStart;
		return $timeDiff;
	}

	/**
	 * Get the configured handler file.
	 */
	function getHandlerFile() {
		return $this->data['handler_file'];
	}

	/**
	 * Check if task is right state for running.
	 */
	function isRunnable() {
		$runnable = false;

		if (!$this->isDisabled()) {
			// Take care of last time (end - on success, start - if timedout)
			$lastTime = ($this->getLastEnd() > 0) ? $this->getLastEnd() : $this->getLastStart();
			$elapsedTime = time() - $lastTime;
			$runnable = ($elapsedTime >= $this->getFrequency());
		}
		return $runnable;
	}

	/**
	 * Helper function to check the status value.
	 */
	function statusEqual($value) {
		$status = intval($this->data['status']);
		return $status == $value;
	}

	/**
	 * Is task in running status?
	 */
	function isRunning() {
		return $this->statusEqual(self::$STATUS_RUNNING);
	}

	/**
	 * Is task enabled?
	 */
	function isEnabled() {
		return $this->statusEqual(self::$STATUS_ENABLED);
	}

	/**
	 * Is task disabled?
	 */
	function isDisabled() {
		return $this->statusEqual(self::$STATUS_DISABLED);
	}

	/**
	 * Update status
	 */
	function updateStatus($status) {
		switch (intval($status)) {
			case self::$STATUS_DISABLED:
			case self::$STATUS_ENABLED:
			case self::$STATUS_RUNNING:
				break;
			default:
				throw new Exception('Invalid status');
		}
		self::querySilent('UPDATE vtiger_cron_task SET status=? WHERE id=?', array($status, $this->getId()));
	}

	/**
	 * Mark this instance as running.
	 */
	function markRunning() {
		self::querySilent('UPDATE vtiger_cron_task SET status=?, laststart=?, lastend=? WHERE id=?', array(self::$STATUS_RUNNING, time(), 0, $this->getId()));
		return $this;
	}

	/**
	 * Mark this instance as finished.
	 */
	function markFinished() {
		self::querySilent('UPDATE vtiger_cron_task SET status=?, lastend=? WHERE id=?', array(self::$STATUS_ENABLED, time(), $this->getId()));
		return $this;
	}

	/**
	 * Set the bulkMode flag
	 */
	function setBulkMode($mode = null) {
		$this->bulkMode = $mode;
	}

	/**
	 * Is task in bulk mode execution?
	 */
	function inBulkMode() {
		return $this->bulkMode;
	}

	/**
	 * Detect if the task was started by never finished.
	 */
	function hadTimedout() {
		return intval($this->data['lastend']) === 0;
	}

	/**
	 * Execute SQL query silently (even when table doesn't exist)
	 */
	protected static function querySilent($sql, $params=false) {
		global $adb;
		$old_dieOnError = $adb->dieOnError;

		$adb->dieOnError = false;
		$result = $adb->pquery($sql, $params);
		$adb->dieOnError = $old_dieOnError;
		return $result;
	}

	/**
	 * Initialize the schema.
	 */
	protected static function initializeSchema() {
		if (!self::$schemaInitialized) {
			if (!Vtiger_Utils::CheckTable('vtiger_cron_task')) {
				Vtiger_Utils::CreateTable('vtiger_cron_task',
								'(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					name VARCHAR(100) UNIQUE KEY, handler_file VARCHAR(100),
					frequency int, laststart long, lastend long, status int)',
								true);
			}
			self::$schemaInitialized = true;
		}
	}

	/**
	 * Register cron task.
	 */
	static function register($name, $handler_file, $frequency) {
		self::initializeSchema();

		$instance = self::getInstance($name);
		if ($instance === false) {
			self::querySilent('INSERT INTO vtiger_cron_task (name, handler_file, frequency, status) VALUES(?,?,?,?)',
							array($name, $handler_file, $frequency, self::$STATUS_ENABLED));
			return true;
		}
		return false;
	}

	/**
	 * De-register cron task.
	 */
	static function deregister($name) {
		self::querySilent('DELETE FROM vtiger_cron_task WHERE name=?', array($name));
		if (isset(self::$instanceCache[$name])) {
			unset(self::$instanceCache[$name]);
		}
	}

	/**
	 * Get instances that are active (not disabled)
	 */
	static function listAllActiveInstances() {
		global $adb;

		$instances = array();
		$result = self::querySilent('SELECT * FROM vtiger_cron_task WHERE status <> ?', array(self::$STATUS_DISABLED));
		if ($result && $adb->num_rows($result)) {
			while ($row = $adb->fetch_array($result)) {
				$instances[] = new Vtiger_Cron($row);
			}
		}
		return $instances;
	}

	/**
	 * Get instance of cron task.
	 */
	static function getInstance($name) {
		global $adb;

		$instance = false;
		if (isset(self::$instanceCache[$name])) {
			$instance = self::$instanceCache[$name];
		}

		if ($instance === false) {
			$result = self::querySilent('SELECT * FROM vtiger_cron_task WHERE name=?', array($name));
			if ($result && $adb->num_rows($result)) {
				$instance = new Vtiger_Cron($adb->fetch_array($result));
			}
		}
		return $instance;
	}

}

?>