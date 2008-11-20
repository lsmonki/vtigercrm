function editworkflowscript($, fieldNames, fieldTypes, conditions){
	var vtinst = new VtigerWebservices("webservice.php");
	var desc = null;
	
	function id(v){
		return v;
	}
	
	function map(fn, list){
		var out = [];
		$.each(list, function(i, v){
			out[out.length]=fn(v);
		});
		return out;
	}
	
	function field(name){
		return function(object){
			return object[name];
		}
	}
	
	function zip(){
		var out = [];
		
		var lengths = map(field('length'), arguments);
		var min = reduceR(function(a,b){return a<b?a:b},lengths,lengths[0]);
		for(var i=0; i<min; i++){
			out[i]=map(field(i), arguments);
		}
		return out;
	}
	
	function dict(list){
		var out = {};
		$.each(list, function(i, v){
			out[v[0]] = v[1];
		});
		return out;
	}
	
	function filter(pred, list){
		var out = [];
		$.each(list, function(i, v){
			if(pred(v)){
				out[out.length]=v;
			}
		});
		return out;
	}
	
	
	
	function reduceR(fn, list, start){
		var acc = start;
		$.each(list, function(i, v){
			acc = fn(acc, v);
		});
		return acc;
	}
	
	function contains(list, value){
		var ans = false;
		$.each(list, function(i, v){
			if(v==value){
				ans = true;
				return false;
			}
		});
		return ans;
	}
	
	function concat(lista,listb){
		return lista.concat(listb);
	}
	
	function errorDialog(message){
		alert(message);
	}
	
	function handleError(fn){
		return function(status, result){
			if(status){
				fn(result);
			}else{
				errorDialog('Failure:'+result);
			}
		}
	}
	
	function mergeObjects(obj1, obj2){
		var res = {};
		for(var k in obj1){
			res[k] = obj1[k];
		}
		for(var k in obj2){
			res[k] = obj2[k];
		}
		return res;
	}
	
	function jsonget(operation, params, callback){
		var obj = {
				module:'com_vtiger_workflow', 
				action:'com_vtiger_workflowAjax',	
				file:operation, ajax:'true'};
		$.each(params,function(key, value){
			obj[key] = value;
		});
		$.get('index.php', obj, 
			function(result){
				var parsed = JSON.parse(result);
				callback(parsed);
		});
	}
	
	

	
	function center(el){
		el.css({position: 'fixed'});
		var height = el.height();
		var width = el.width();
		el.css({
			position: 'fixed',
			top: '50%',
			left: '50%',
			'margin-left': (-width/2)+'px',
			'margin-top': (-height/2)+'px'
		});
	}
	
	
	
	function NewTaskPopup(){
		function close(){
			$('#new_task_popup').css('display', 'none');
		}

		function show(module){
			$('#new_task_popup').css('display', 'block');
			center($('#new_task_popup'));
		}
		
		$('#new_task_popup_close').click(close);
		$('#new_task_popup_cancel').click(close);
		return {
			close:close,show:show
		}
	}
	
	
	
	var operations = {
		string:["is", "contains", "does not contain", "starts with", "ends with"],
		integer:["equal to", "less than", "greater than", "does not equal", "less than or equal to", "greater than or equal to"],
		double:["equal to", "less than", "greater than", "does not equal", "less than or equal to", "greater than or equal to"],
		reference:["is"],
		url:["is", "contains", "does not contain", "starts with", "ends with"],
		picklist:['is'],
		datetime:['is'],
		email:["is", "contains", "does not contain", "starts with", "ends with"],
		phone:["is", "contains", "does not contain", "starts with", "ends with"],
		date:["is"],
		time:["is"],
	};
	
	function defaultValue(fieldType){
		var functions = {
			string:function(opType, condno){
				var value = $(format("#save_condition_%s_value", condno));
				value.replaceWith(format('<input type="text" id="save_condition_%s_value" value="" class="value">', condno));
			},
			integer:function(opType, condno){
				var value = $(format("#save_condition_%s_value", condno));
				value.replaceWith(format('<input type="text" id="save_condition_%s_value" value="0" class="value">', condno));
			},
			reference:function(opType, condno){
				defaultValue('String')(opType, condno);
				// value = $(format("#save_condition_%s_value", condno));
				// value.replaceWith('<div id="save_condition_%s_value" class="value save_condition"></div>');
				/*var value = $(format("#save_condition_%s_value", condno));
				value.replaceWith(format('\
							<div id="save_condition_%s_value" class="value save_condition"> \
							<input type="text" id="save_condition_%s_value_label" readonly style="width:50px"> \
							<span id="save_condition_%s_value_change" class="select_entity"> \
							<img src="themes/softed/images/select.gif"></span> \
							<input type="hidden" id="save_condition_%s_value_id" value="" class="value"> \
							</div>', condno, condno, condno, condno));
				$(format("#save_condition_%s_value_change", condno)).bind("click", function(){
					vtInst.describeOBject(opType.relatedTo[0], function(status, result){
						if(status){
						
						}else{
							errorMessage("Web service failed");
						}
					});
					// var win = window.open(format("index.php?module=%s&action=Popup", opType.relatedTo), 
					// 					"test", "width=640, height=602, resizable=0, scrollbars=0");
					// 				$(win.document).load(function(){
					// 					$(win.document.body).append("Hello World");
					// 				});
				
				});*/
			}
		};
		var ret = functions[fieldType];
		if(ret==null){
			ret = functions['string'];
		}
		return ret;
	}
	
	var format = fn.format;
	
	
	function fillOptions(el,options){
		el.empty();
		$.each(options, function(k, v){
			el.append('<option value="'+k+'">'+v+'</option>');
		});
	}
	
	function resetFields(opType, condno){
		var ops = $("#save_condition_"+condno+"_operation");
		var selectedOperations = operations[opType.name];
		var l = dict(zip(selectedOperations, selectedOperations));
		fillOptions(ops, l);
		defaultValue(opType.name)(opType, condno);
	}
	
	function removeCondition(condno){
		if($("#save_conditions").children().size()>1){
			$(format("#save_condition_%s", condno)).remove();
		}
	}
	

	
	

	
	//var fieldNames=null;
	//var fieldTypes=null;

	
	function getDescribeObjects(moduleName, callback){
		vtinst.describeObject(moduleName, handleError(function(result){
			var parent = result;
			var fields = parent['fields'];
			var referenceFields = filter(function(e){return e['type']['name']=='reference';}, fields);
			var referenceFieldModules = map(function(e){return e['type']['refersTo'];}, referenceFields);
			function union(a, b){
				newfields = filter(function(e){return !contains(a, e);}, b)
				return a.concat(newfields);
			}
			var relatedModules = reduceR(union, referenceFieldModules, [parent['name']]);
			
			function executer(parameters){
				var failures = filter(function(e){return e[0]==false}, parameters);
				if(failures.length!=0){
					var firstFailure = failures[0];
					callback(false, firstFailure[1]);
				}else{
					var moduleDescriptions = map(function(e){return e[1];}, parameters);
					var modules = dict(map(function(e){return [e['name'], e];}, moduleDescriptions));
					callback(true, modules);
				}
			}
			p = parallelExecuter(executer, relatedModules.length);
			$.each(relatedModules, function(i, v){
				p(function(callback){vtinst.describeObject(v, callback);});
			});
		}));
	}
	
	$(document).ready(function(){
		vtinst.extendSession(handleError(function(result){
			getDescribeObjects(moduleName, handleError(function(modules){
				var parent = modules[moduleName];
				var parentFields = map(function(e){return[e['name'],e['label']];}, parent['fields']);
				var referenceFieldTypes = filter(function(e){return e['type']['name']=='reference';}, parent['fields']);
				var moduleFieldTypes = {};
				$.each(modules, function(k, v){
					moduleFieldTypes[k] = dict(map(function(e){return [e['name'], e['type']];}, v['fields']));
				});
				
				function getFieldType(fullFieldName){
					var group = fullFieldName.match(/(\w+) : \((\w+)\) (\w+)/);
					if(group==null){
						var fieldModule = moduleName;
						var fieldName = fullFieldName;
					}else{
						var fieldModule = group[2];
						var fieldName = group[3];
					}
					return moduleFieldTypes[fieldModule][fieldName];
				}
				
				function fieldReferenceNames(referenceField){
					var name = referenceField['name'];
					var label = referenceField['label'];
					function forModule(moduleName){
						return map(function(field){
							return [name+' : '+'('+moduleName+') '+field['name'],
									label+' : '+'('+moduleName+') '+field['label']];},
							modules[moduleName]['fields']);
					}
					return reduceR(concat, map(forModule,referenceField['type']['refersTo']),[]);
				}
				
				
				var referenceFields = reduceR(concat, map(fieldReferenceNames, referenceFieldTypes), []);
				var fieldLabels = dict(parentFields.concat(referenceFields));
				
				
				function addCondition(condno){
					$("#save_conditions").append(
						'<div id="save_condition_'+condno+'"> \
							<select id="save_condition_'+condno+'_fieldname" class="fieldname"></select> \
							<select id="save_condition_'+condno+'_operation" class="operation"></select> \
							<input type="text" id="save_condition_'+condno+'_value" class="value"> \
							<span id="save_condition_'+condno+'_remove" class="link"> \
							<img src="modules/com_vtiger_workflow/resources/remove.png"></span> \
						</div>'
					);
					var fe = $("#save_condition_"+condno+"_fieldname");
					var i = 1;
					fillOptions(fe, fieldLabels);
					
					
					var fullFieldName = fe.attr("value");
					
					resetFields(getFieldType(fullFieldName), condno);

					var re = $("#save_condition_"+condno+"_remove");
					re.bind("click", function(){
						removeCondition(condno);
					});

					fe.find("option").bind("click", function(){ 
						var select = $(this).parent();
						var condNo = select.attr("id").match(/save_condition_(\d+)_fieldname/)[1];
						var fullFieldName = $(this).attr('value');
						resetFields(getFieldType(fullFieldName), condNo);
					});
				}
				
				newTaskPopup = NewTaskPopup();
				$("#new_task").click(function(){
					newTaskPopup.show();
				});
				var condno=0;
				if(conditions){
					$.each(conditions, function(i, condition){
						var fieldname = condition["fieldname"];
						addCondition(condno);
						$(format("#save_condition_%s_fieldname", condno)).attr("value", fieldname);
						resetFields(getFieldType(fieldname), condno);
						$(format("#save_condition_%s_operation", condno)).attr("value", condition["operation"]);
						$(format("#save_condition_%s_value", condno)).attr("value", condition["value"]);
						condno+=1;
					});
				}else{
					addCondition(condno++);
				}	
				$("#save_conditions_add").bind("click", function(){
					addCondition(condno++);
				});
		
				$("#save_submit").bind("click", function(){
					var conditions = [];
					$("#save_conditions").children().each(function(i){
						var fieldname = $(this).children(".fieldname").attr("value");
						var operation = $(this).children(".operation").attr("value");
						var value = $(this).children(".value").attr("value");
						condition = {fieldname:fieldname, operation:operation, value:value};
						conditions[i]=condition;
					});
					out = JSON.stringify(conditions);
					$("#save_conditions_json").attr("value", out);
				});
			}));
		}));
	});

}