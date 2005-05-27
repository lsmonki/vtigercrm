<?php 
// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// $Id: class.Linux.inc.php,v 1.37 2004/10/13 08:13:29 webbie Exp $
class sysinfo {
  // get our apache SERVER_NAME or vhost
  function vhostname () {
    if (! ($result = getenv('SERVER_NAME'))) {
      $result = 'N.A.';
    } 
    return $result;
  } 
  // get our canonical hostname
  function chostname () {
    if ($fp = fopen('/proc/sys/kernel/hostname', 'r')) {
      $result = trim(fgets($fp, 4096));
      fclose($fp);
      $result = gethostbyaddr(gethostbyname($result));
    } else {
      $result = 'N.A.';
    } 
    return $result;
  } 
  // get the IP address of our canonical hostname
  function ip_addr () {
    if (!($result = getenv('SERVER_ADDR'))) {
      $result = gethostbyname($this->chostname());
    } 
    return $result;
  } 

  function kernel () {
    if ($fd = fopen('/proc/version', 'r')) {
      $buf = fgets($fd, 4096);
      fclose($fd);

      if (preg_match('/version (.*?) /', $buf, $ar_buf)) {
        $result = $ar_buf[1];

        if (preg_match('/SMP/', $buf)) {
          $result .= ' (SMP)';
        } 
      } else {
        $result = 'N.A.';
      } 
    } else {
      $result = 'N.A.';
    } 
    return $result;
  } 
  
  function uptime () {
    $fd = fopen('/proc/uptime', 'r');
    $ar_buf = split(' ', fgets($fd, 4096));
    fclose($fd);

    $result = trim($ar_buf[0]);

    return $result;
  } 

  function users () {
    $who = split('=', execute_program('who', '-q'));
    $result = $who[1];
    return $result;
  } 

  function loadavg () {
    if ($fd = fopen('/proc/loadavg', 'r')) {
      $results = split(' ', fgets($fd, 4096));
      fclose($fd);
    } else {
      $results = array('N.A.', 'N.A.', 'N.A.');
    } 
    return $results;
  } 

  function cpu_info () {
    $results = array();
    $ar_buf = array();

    if ($fd = fopen('/proc/cpuinfo', 'r')) {
      while ($buf = fgets($fd, 4096)) {
        list($key, $value) = preg_split('/\s+:\s+/', trim($buf), 2); 
        // All of the tags here are highly architecture dependant.
        // the only way I could reconstruct them for machines I don't
        // have is to browse the kernel source.  So if your arch isn't
        // supported, tell me you want it written in.
        switch ($key) {
          case 'model name':
            $results['model'] = $value;
            break;
          case 'cpu MHz':
            $results['cpuspeed'] = sprintf('%.2f', $value);
            break;
          case 'cycle frequency [Hz]': // For Alpha arch - 2.2.x
            $results['cpuspeed'] = sprintf('%.2f', $value / 1000000);
            break;
          case 'clock': // For PPC arch (damn borked POS)
            $results['cpuspeed'] = sprintf('%.2f', $value);
            break;
          case 'cpu': // For PPC arch (damn borked POS)
            $results['model'] = $value;
            break;
          case 'L2 cache': // More for PPC
            $results['cache'] = $value;
            break;
          case 'revision': // For PPC arch (damn borked POS)
            $results['model'] .= ' ( rev: ' . $value . ')';
            break;
          case 'cpu model': // For Alpha arch - 2.2.x
            $results['model'] .= ' (' . $value . ')';
            break;
          case 'cache size':
            $results['cache'] = $value;
            break;
          case 'bogomips':
            $results['bogomips'] += $value;
            break;
          case 'BogoMIPS': // For alpha arch - 2.2.x
            $results['bogomips'] += $value;
            break;
          case 'BogoMips': // For sparc arch
            $results['bogomips'] += $value;
            break;
          case 'cpus detected': // For Alpha arch - 2.2.x
            $results['cpus'] += $value;
            break;
          case 'system type': // Alpha arch - 2.2.x
            $results['model'] .= ', ' . $value . ' ';
            break;
          case 'platform string': // Alpha arch - 2.2.x
            $results['model'] .= ' (' . $value . ')';
            break;
          case 'processor':
            $results['cpus'] += 1;
            break;
          case 'Cpu0ClkTck': // Linux sparc64
            $results['cpuspeed'] = sprintf('%.2f', hexdec($value) / 1000000);
            break;
          case 'Cpu0Bogo': // Linux sparc64 & sparc32
            $results['bogomips'] = $value;
            break;
          case 'ncpus probed': // Linux sparc64 & sparc32
            $results['cpus'] = $value;
            break;
        } 
      } 
      fclose($fd);
    } 

    $keys = array_keys($results);
    $keys2be = array('model', 'cpuspeed', 'cache', 'bogomips', 'cpus');

    while ($ar_buf = each($keys2be)) {
      if (! in_array($ar_buf[1], $keys)) {
        $results[$ar_buf[1]] = 'N.A.';
      } 
    } 
    return $results;
  } 

  function pci () {
    $results = array();

    if ($_results = execute_program('lspci')) {
      $lines = split("\n", $_results);
      for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
        list($addr, $name) = explode(' ', trim($lines[$i]), 2);

        if (!preg_match('/bridge/i', $name) && !preg_match('/USB/i', $name)) {
          // remove all the version strings
          $name = preg_replace('/\(.*\)/', '', $name);
          $results[] = $addr . ' ' . $name;
        } 
      } 
    } elseif ($fd = fopen('/proc/pci', 'r')) {
      while ($buf = fgets($fd, 4096)) {
        if (preg_match('/Bus/', $buf)) {
          $device = 1;
          continue;
        } 

        if ($device) {
          list($key, $value) = split(': ', $buf, 2);

          if (!preg_match('/bridge/i', $key) && !preg_match('/USB/i', $key)) {
            $results[] = preg_replace('/\([^\)]+\)\.$/', '', trim($value));
          } 
          $device = 0;
        } 
      } 
    } 
    asort($results);
    return $results;
  } 

  function ide () {
    $results = array();

    $handle = opendir('/proc/ide');

    while ($file = readdir($handle)) {
      if (preg_match('/^hd/', $file)) {
        $results[$file] = array(); 
        // Check if device is CD-ROM (CD-ROM capacity shows as 1024 GB)
        if ($fd = fopen("/proc/ide/$file/media", 'r')) {
          $results[$file]['media'] = trim(fgets($fd, 4096));
          if ($results[$file]['media'] == 'disk') {
            $results[$file]['media'] = 'Hard Disk';
          } 

          if ($results[$file]['media'] == 'cdrom') {
            $results[$file]['media'] = 'CD-ROM';
          } 
          fclose($fd);
        } 

        if ($fd = fopen("/proc/ide/$file/model", 'r')) {
          $results[$file]['model'] = trim(fgets($fd, 4096));
          if (preg_match('/WDC/', $results[$file]['model'])) {
            $results[$file]['manufacture'] = 'Western Digital';
          } elseif (preg_match('/IBM/', $results[$file]['model'])) {
            $results[$file]['manufacture'] = 'IBM';
          } elseif (preg_match('/FUJITSU/', $results[$file]['model'])) {
            $results[$file]['manufacture'] = 'Fujitsu';
          } else {
            $results[$file]['manufacture'] = 'Unknown';
          } 

          fclose($fd);
        } 

        if ($fd = fopen("/proc/ide/$file/capacity", 'r')) {
          $results[$file]['capacity'] = trim(fgets($fd, 4096));
          if ($results[$file]['media'] == 'CD-ROM') {
            unset($results[$file]['capacity']);
          } 
          fclose($fd);
        } 
      } 
    } 
    closedir($handle);

    asort($results);
    return $results;
  } 

  function scsi () {
    $results = array();
    $dev_vendor = '';
    $dev_model = '';
    $dev_rev = '';
    $dev_type = '';
    $s = 1;

    if ($fd = fopen('/proc/scsi/scsi', 'r')) {
      while ($buf = fgets($fd, 4096)) {
        if (preg_match('/Vendor/', $buf)) {
          preg_match('/Vendor: (.*) Model: (.*) Rev: (.*)/i', $buf, $dev);
          list($key, $value) = split(': ', $buf, 2);
          $dev_str = $value;
          $get_type = 1;
          continue;
        } 

        if ($get_type) {
          preg_match('/Type:\s+(\S+)/i', $buf, $dev_type);
          $results[$s]['model'] = "$dev[1] $dev[2] ($dev_type[1])";
          $results[$s]['media'] = "Hard Disk";
          $s++;
          $get_type = 0;
        } 
      } 
    } 
    asort($results);
    return $results;
  } 

  function usb () {
    $results = array();
    $devstring = 0;
    $devnum = -1;

    if ($fd = fopen('/proc/bus/usb/devices', 'r')) {
      while ($buf = fgets($fd, 4096)) {
        if (preg_match('/^T/', $buf)) {
          $devnum += 1;
        } 
        if (preg_match('/^S/', $buf)) {
          $devstring = 1;
        } 

        if ($devstring) {
          list($key, $value) = split(': ', $buf, 2);
          list($key, $value2) = split('=', $value, 2);
          $results[$devnum] .= " " . trim($value2);
          $devstring = 0;
        } 
      } 
    } 
    return $results;
  } 

  function sbus () {
    $results = array();
    $_results[0] = ""; 
    // TODO. Nothing here yet. Move along.
    $results = $_results;
    return $results;
  } 

  function network () {
    $results = array();

    if ($fd = fopen('/proc/net/dev', 'r')) {
      while ($buf = fgets($fd, 4096)) {
        if (preg_match('/:/', $buf)) {
          list($dev_name, $stats_list) = preg_split('/:/', $buf, 2);
          $stats = preg_split('/\s+/', trim($stats_list));
          $results[$dev_name] = array();

          $results[$dev_name]['rx_bytes'] = $stats[0];
          $results[$dev_name]['rx_packets'] = $stats[1];
          $results[$dev_name]['rx_errs'] = $stats[2];
          $results[$dev_name]['rx_drop'] = $stats[3];

          $results[$dev_name]['tx_bytes'] = $stats[8];
          $results[$dev_name]['tx_packets'] = $stats[9];
          $results[$dev_name]['tx_errs'] = $stats[10];
          $results[$dev_name]['tx_drop'] = $stats[11];

          $results[$dev_name]['errs'] = $stats[2] + $stats[10];
          $results[$dev_name]['drop'] = $stats[3] + $stats[11];
        } 
      } 
    } 
    return $results;
  } 

  function memory () {
    if ($fd = fopen('/proc/meminfo', 'r')) {
      $results['ram'] = array();
      $results['swap'] = array();
      $results['devswap'] = array();

      while ($buf = fgets($fd, 4096)) {
        if (preg_match('/^MemTotal:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
          $results['ram']['total'] = $ar_buf[1];
        } else if (preg_match('/^MemFree:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
          $results['ram']['free'] = $ar_buf[1];
        } else if (preg_match('/^Cached:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
          $results['ram']['cached'] = $ar_buf[1];
        } else if (preg_match('/^Buffers:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
          $results['ram']['buffers'] = $ar_buf[1];
        } else if (preg_match('/^SwapTotal:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
          $results['swap']['total'] = $ar_buf[1];
        } else if (preg_match('/^SwapFree:\s+(.*)\s*kB/i', $buf, $ar_buf)) {
          $results['swap']['free'] = $ar_buf[1];
        } 
      } 
      $results['ram']['shared'] = 0;
      $results['ram']['used'] = $results['ram']['total'] - $results['ram']['free'];
      $results['swap']['used'] = $results['swap']['total'] - $results['swap']['free'];
      fclose($fd);
      $swaps = file ('/proc/swaps');
      $swapdevs = split("\n", $swaps);

      for ($i = 1; $i < (sizeof($swapdevs) - 1); $i++) {
        $ar_buf = preg_split('/\s+/', $swapdevs[$i], 6);

        $results['devswap'][$i - 1] = array();
        $results['devswap'][$i - 1]['dev'] = $ar_buf[0];
        $results['devswap'][$i - 1]['total'] = $ar_buf[2];
        $results['devswap'][$i - 1]['used'] = $ar_buf[3];
        $results['devswap'][$i - 1]['free'] = ($results['devswap'][$i - 1]['total'] - $results['devswap'][$i - 1]['used']);
        $results['devswap'][$i - 1]['percent'] = round(($ar_buf[3] * 100) / $ar_buf[2]);
      } 
      // I don't like this since buffers and cache really aren't
      // 'used' per say, but I get too many emails about it.
      $results['ram']['t_used'] = $results['ram']['used'];
      $results['ram']['t_free'] = $results['ram']['total'] - $results['ram']['t_used'];
      $results['ram']['percent'] = round(($results['ram']['t_used'] * 100) / $results['ram']['total']);
      $results['swap']['percent'] = round(($results['swap']['used'] * 100) / $results['swap']['total']);
    } else {
      $results['ram'] = array();
      $results['swap'] = array();
      $results['devswap'] = array();
    } 
    return $results;
  } 

  function filesystems () {
    $df = execute_program('df', '-kP');
    $mounts = split("\n", $df);
    $fstype = array();

    if ($fd = fopen('/proc/mounts', 'r')) {
      while ($buf = fgets($fd, 4096)) {
        list($dev, $mpoint, $type) = preg_split('/\s+/', trim($buf), 4);
        $fstype[$mpoint] = $type;
        $fsdev[$dev] = $type;
      } 
      fclose($fd);
    } 

    for ($i = 1, $max = sizeof($mounts); $i < $max; $i++) {
      $ar_buf = preg_split('/\s+/', $mounts[$i], 6);

      $results[$i - 1] = array();

      $results[$i - 1]['disk'] = $ar_buf[0];
      $results[$i - 1]['size'] = $ar_buf[1];
      $results[$i - 1]['used'] = $ar_buf[2];
      $results[$i - 1]['free'] = $ar_buf[3];
      $results[$i - 1]['percent'] = round(($results[$i - 1]['used'] * 100) / $results[$i - 1]['size']) . '%';
      $results[$i - 1]['mount'] = $ar_buf[5];
      ($fstype[$ar_buf[5]]) ? $results[$i - 1]['fstype'] = $fstype[$ar_buf[5]] : $results[$i - 1]['fstype'] = $fsdev[$ar_buf[0]];
    } 
    return $results;
  } 

  function distro () {
   if ($fd = fopen('/etc/debian_version', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = 'Debian ' . trim($buf);
   } elseif ($fd = fopen('/etc/SuSE-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/mandrake-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/fedora-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/redhat-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/gentoo-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/slackware-version', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/eos-version', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/trustix-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/arch-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } elseif ($fd = fopen('/etc/cobalt-release', 'r')) {
      $buf = fgets($fd, 1024);
      fclose($fd);
      $result = trim($buf);
   } else {
      $result = 'N.A.';
   }
   return $result;
  }

  function distroicon () {   
   if (file_exists('/etc/debian_version')) {
      $result = 'Debian.gif';
   } elseif (file_exists('/etc/SuSE-release')) {
      $result = 'Suse.gif';
   } elseif (file_exists('/etc/mandrake-release')) {
      $result = 'Mandrake.gif';
   } elseif (file_exists('/etc/fedora-release')) {
      $result = 'Fedora.gif';
   } elseif (file_exists('/etc/redhat-release')) {
      $result = 'Redhat.gif';
   } elseif (file_exists('/etc/gentoo-release')) {
      $result = 'Gentoo.gif';
   } elseif (file_exists('/etc/slackware-version')) {
      $result = 'Slackware.gif';
   } elseif (file_exists('/etc/eos-version')) {
      $result = 'free-eos.gif';
   } elseif (file_exists('/etc/trustix-release')) {
      $result = 'Trustix.gif';
   } elseif (file_exists('/etc/arch-release')) {
      $result = 'Arch.gif';
   } elseif (file_exists('/etc/cobalt-release')) {
      $result = 'Cobalt.gif';
   } else {
      $result = 'xp.gif';
   }
   return $result;
  }

} 

?>
