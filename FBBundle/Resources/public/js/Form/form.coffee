create_form = ()->
	data_params =
		count : '8'
		title: 'First Form Creation'
		description:  'Checking Guys'
		form_alignment: 'form-vertical'
		field_dict:
			field1:
				id: 'text_test1',
				type: 'text',
				hidden: ''	,
				name: 'text_test1',
				maxlength: '35',
				size: '35',
				label:
					id: 'test_label1',
					name: 'test_label1',
					for_attr: 'text_test1',
					hidden: '',
					value: 'Test Text'
			field2:
				id: 'textarea_test2',
				type: 'textarea',
				hidden: ''	,
				name: 'textarea_test2',
				disabled: '',
				readonly: '',
				rows: '4',
				cols: '4'
				label:
					id: 'test_label2',
					name: 'test_label2',
					for_attr: 'textarea_test2',
					hidden: '',
					value: 'Test Textarea'
			field3:
				id: 'password_test3',
				type: 'password',
				hidden: ''	,
				name: 'password_test3',
				maxlength: '35',
				size: '35'
				label:
					id: 'test_label3',
					name: 'test_label3',
					for_attr: 'password_test',
					hidden: '',
					value: 'Test Password'
			field4:
				id: 'checkbox_test4',
				type: 'checkboxgroup',
				hidden: ''	,
				name: 'checkbox_test4',
				checkboxes:
					checkbox1:
						id: 'cbox1',
						name: 'cbox1'
						hidden: '',
						value: 'cbox1'
						label:
							id:'COption1'
							value:'Option1'
					checkbox2:
						id: 'cbox2',
						name: 'cbox2',
						hidden: '',
						value: 'cbox2'
						label:
							id:'COption2'
							value:'COption2'
				label:
					id: 'test_label4',
					name: 'test_label4',
					for_attr: 'checkbox_test4',
					hidden: '',
					value: 'Please slect multiple choices'
			field5:
				id: 'radiobox_test5',
				type: 'radioboxgroup',
				hidden: ''	,
				name: 'radiobox_test5',
				radioboxes:
					radiobox2:
						id: 'rbox1'
						hidden: ''
						value: 'radio1'
						checked: 'checked'
						label:
							id:'ROption1'
							value:'ROption1'
					radiobox3:
						id: 'rbox2',
						hidden: '',
						value: 'radio2'
						label:
							id:'ROption2'
							value:'ROption2'
				label:
					id: 'test_label5',
					name: 'test_label5',
					for_attr: 'radiobox_test5',
					hidden: '',
					value: 'Please slect your choice'
			field6:
				id: 'select_test6',
				type: 'select',
				hidden: ''	,
				name: 'select_test6',
				options:
					option3:
						id: 'soption1',
						name: 'soption1',
						hidden: '',
						disabled: '',
						value: 'Bus'
					option2:
						id: 'soption2',
						name: 'soption2',
						hidden: '',
						disabled: '',
						value:'Volvo'
					
				label:
					id: 'test_label6',
					name: 'test_label6',
					for_attr: 'select_test6',
					hidden: '',
					value: 'Select field'
			field7:
				id: 'submit_test7',
				type: 'submit',
				hidden: ''	,
				name: 'submit_test7',
				value: 'Submit'
			field8:
				id: 'reset_test8',
				type: 'reset',
				hidden: ''	,
				name: 'reset_test8',
				value: 'Test Reset',
	
	error_function = (obj, txt) ->
		alert('Error in creating form')
	success_function = (response) ->
		if response.error
			alert('Error in creating form ....')
		else if response.success
			alert('creating form ....' + response.form_id)
			#window.location.href = '/Symfony/web/app_dev.php/login'
	ajax_params =
		url : '/create_form'
		type : 'POST'
		data : data_params
		dataType : 'json'
		success : success_function
		error : error_function
	$.ajax ajax_params

save_form_data = ()->
	data_to_be_send = {}
	form_data = $("form").serializeArray()
	for x in form_data
		data_to_be_send[x.name] = x.value
	data_params =
		form_id:$('.well').attr('id')
		data_dict: data_to_be_send
				
	error_function = (obj, txt) ->
		alert('Error in saving form data')
		return false
	success_function = (response) ->
		if response.error
			alert('Error in saving form data....')
		else if response.success
			$('form').hide()
			$('#thankyou_message').show()
		return false
			#window.location.href = '/Symfony/web/app_dev.php/login'
	ajax_params =
		url : '/save_form_data'
		type : 'POST'
		data : data_params
		dataType : 'json'
		success : success_function
		error : error_function
	$.ajax ajax_params
	return false

delete_form = (form_id)->
	
	data_params =
		form_id:form_id
	error_function = (obj, txt) ->
		alert('Error in deleting form')
	success_function = (response) ->
		if response.error
			alert('Error in deleting form...Please try again')
		else if response.success
			alert('form deleted....')
		window.location.href = response.redirect_url
	ajax_params =
		url : '/delete'
		type : 'POST'
		data : data_params
		dataType : 'json'
		success : success_function
		error : error_function
	$.ajax ajax_params
	return false

window.create_form = create_form
window.save_form_data = save_form_data
window.delete_form =  delete_form