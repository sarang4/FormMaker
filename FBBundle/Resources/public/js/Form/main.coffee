root = exports ? this

FormEvents =
    EVENT_TYPE_ADD_INPUT:'add_input_item'
    EVENT_TYPE_SAVE_FORM:'save_form'

_.extend(FormEvents, Backbone.Events)

class FieldSettings
    @get_field_defaults:(type)->
	    switch type
		    when 'text'
			    return{
				    id:''
				    type:'text'
				    label:
					    id:''
					    value:'Single Line Text Input'
				    hidden:false
				    maxlength:''
			    }
		    when 'number'
			    return{
				    id:''
				    type:'number'
				    label:
					    id:''
					    value:'Number'
				    hidden:false
				    maxlength:''
			    }
		    when 'textarea'
			    return{
				    id:''
				    type:'textarea'
				    label:
					    id:''
					    value:'Paragraph Text Input'
				    hidden:false
			    }
		    when 'select'
			    return {
				    id:''
				    type:'select'
				    options:
					    option1:
						    id:'option1'
						    name:'option1'
						    value:'Option1'
					    option2:
						    id:'option2'
						    name:'option2'
						    value:'Option2'
					    option3:
						    id:'option3'
						    name:'option3'
						    value:'Option3'
				    label:
					    id:''
					    value:'Select Field'
				    hidden:false
			    }
		    when 'radiobox'
			    return {
				    id:''
				    name:''
				    type:'radioboxgroup'
				    radioboxes:
					    option1:
						    id:'option1'
						    name:'option1'
						    value:'Option1'
						    label:
							    id:'rdo_label1'
							    value:'Option1'
					    option2:
						    id:'option2'
						    name:'option2'
						    value:'Option2'
						    label:
							    id:'rdo_label2'
							    value:'Option2'
					    option3:
						    id:'option3'
						    name:'option3'
						    value:'Option3'
						    label:
							    id:'rdo_label3'
							    value:'Option3'
				    label:
					    id:''
					    value:'Single Option Select'
				    hidden:false
			    }
		    when 'checkbox'
			    return {
				    id:''
				    type:'checkboxgroup'
				    name:''
				    checkboxes:
					    option1:
						    id:'option1'
						    name:'option1'
						    value:'Option1'
						    label:
							    id:'chk_label1'
							    value:'Option1'
					    option2:
						    id:'option2'
						    name:'option2'
						    value:'Option2'
						    label:
							    id:'chk_label2'
							    value:'Option2'
					    option3:
						    id:'option3'
						    name:'option3'
						    value:'Option3'
						    label:
							    id:'chk_label3'
							    value:'Option3'
				    label:
					    id:''
					    value:'Multiple Select'
				    hidden:false
			    }
		    else
			    return null


class BaseView extends Backbone.View
    initialize: =>
	    @model.set label_position:'top'
	    @template = $('#form_base').template()
    render:=>
	    $(@el).html $.tmpl(@template, @model.toJSON())

    events:
	    'click .remove_item':'remove_input_element'
	    'click .input_element': 'input_element_settings'
	    'mouseenter .input_element':'show_action_buttons'
	    'mouseleave .input_element':'hide_action_buttons'
	    'keyup #form_title_text': 'change_form_title'
	    'keyup #form_description_text': 'change_form_description'
	    'click .label_align_change': 'label_align_change'
	    'click #field_setting_tab': 'tab_field_setting'
	    'click #save_form': 'save_form_click'

    label_align_change:(ev)=>
	    if $(ev.target).attr('value') =='left'
		    $('#form_panel').find('form').removeClass('form-vertical').addClass('form-horizontal')
		    @model.set form_alignment:'form-horizontal'
	    else
		    $('#form_panel').find('form').removeClass('form-horizontal').addClass('form-vertical')
		    @model.set form_alignment:'form-vertical'

    change_form_title:=>
	    @model.set form_title:$('#form_title_text').val()
	    $('#form_title').html($('#form_title_text').val())

    change_form_description:=>
	    @model.set form_description:$('#form_description_text').val()
	    $('#form_description').html($('#form_description_text').val())

    show_action_buttons:(ev)=>
	    $(ev.target).find('.action_items').removeClass('hide')

    hide_action_buttons:(ev)=>
	    $(ev.target).find('.action_items').addClass('hide')

    remove_input_element:(ev)=>
	    field_settings_dict = @model.get('field_settings')
	    delete field_settings_dict[$(ev.target).closest('.input_element').find('.controls').children(':first').attr('id')]
	    @model.set field_settings:field_settings_dict
	    $(ev.target).closest('.input_element').remove()

    input_element_settings:(ev)=>
	    if $(ev.target).is('.remove_item')
		    return
	    @model.set current_field_edit:$(ev.target).closest('.input_element').find('.controls').children(':first').attr('id')
	    $('.input_element').each (item) ->
		    $(this).removeClass('selected')
	    $(ev.target).closest('.input_element').addClass('selected')
	    if @render_field_settings
		    @render_field_settings.detach()
	    @render_field_settings = new FieldSettingsView el:'#tab3', model:@model, type:$(ev.target).closest('.input_element').attr('type'), field_id:$(ev.target).closest('.input_element').find('.controls').children(':first').attr('id')
	    $('#selection_tabs a:last').tab('show')

    tab_field_setting:=>
	    a = $('#form_panel').find('.input_element:first')

    save_form_click:=>
	    FormEvents.trigger(FormEvents.EVENT_TYPE_SAVE_FORM)

class FieldSettingsView extends Backbone.View
    initialize:=>
	    template_render = @get_template(@.options.type)
	    @template = $(template_render).template()
	    @render()

    events:
	    'keyup .field_label_text': 'change_field_label_text'
	    'change .hide_field_checkbox': 'change_field_hide_status'
	    'change .field_max_chars': 'change_max_chars'

    change_field_label_text:=>
	    $('#form_panel').find('#' + @.options.field_id).closest('.control-group').find('.control-label').html(($('.field_setting').find('.field_label_text:first').val()))
	    field_settings_dict = @model.get('field_settings')
	    field_settings_dict[@.options.field_id].label.value = $('.field_setting').find('.field_label_text').val()
	    @model.set field_settings: field_settings_dict

    change_field_hide_status:(ev)=>
	    field_settings_dict = @model.get('field_settings')
	    if $(ev.target).is(':checked')
		    field_settings_dict[@.options.field_id].hidden = true
	    else
		    field_settings_dict[@.options.field_id].hidden = false
	    @model.set field_settings: field_settings_dict
    change_max_chars:=>
	    field_settings_dict = @model.get('field_settings')
	    field_settings_dict[@.options.field_id].maxlength = $('.field_setting').find('.maxlen').val()
	    @model.set field_settings: field_settings_dict

    get_template:(type)=>
	    switch type
		    when 'text'
			    return '#text_settings_template'
		    when 'textarea'
			    return '#textarea_settings_template'
		    when 'select'
			    return '#select_settings_template'
		    when 'number'
			    return '#number_settings_template'
		    when 'radiobox'
			    return '#radiobox_settings_template'
		    when 'checkbox'
			    return '#checkbox_settings_template'
		    else
			    return None
    render:=>
	    field_settings = @model.get('field_settings')[@.options.field_id]
	    $(@el).html ''
	    $(@el).html $.tmpl(@template, field_settings)

    detach:=>
	    $(@el).unbind()
	    @model.unbind()

class LeftPanelView extends Backbone.View
    initialize:=>
	    @template = $('#left_panel_template').template()
	    @render()

    events:
	    'click a': 'change_tab'
	    'click .input_item': 'add_input_item'
	    'click .add_single_select_option': 'add_single_select_option'
	    'click .delete_single_select_option': 'delete_single_select_option'
	    'click #select_options_done': 'set_select_field_options'
	    'click .add_single_checkbox_option': 'add_single_checkbox_option'
	    'click .delete_single_checkbox_option': 'delete_single_checkbox_option'
	    'click #checkbox_options_done': 'set_checkbox_field_options'
	    'click .add_single_radiobox_option': 'add_single_radiobox_option'
	    'click .delete_single_radiobox_option': 'delete_single_radiobox_option'
	    'click #radiobox_options_done': 'set_radiobox_field_options'

    add_single_select_option:(ev)=>
	    single_select_template = $('#single_select_option_template').template()
	    $(ev.target).closest('.single_select_option').after($.tmpl single_select_template)

    set_select_field_options:=>
	    select_options_dict = {}
	    $('.single_select_option').each (item)->
		    select_options_dict[item]['id']=item
		    select_options_dict[item]['name']=item
	    field_settings_dict = @model.get('field_settings')
	    field_settings_dict[@model.get('current_field_edit')].options = select_options_dict
	    @model.set field_settings:field_settings_dict
	    $('#'+ @model.get('current_field_edit')).html ''
	    for item, options of select_options_dict
		    $('#' + @model.get('current_field_edit')).append("<option>#{options.value}</option>")

    delete_single_select_option:(ev)=>
	    $(ev.target).closest('.single_select_option').remove()

    add_single_checkbox_option:(ev)=>
	    single_checkbox_template = $('#single_checkbox_option_template').template()
	    $(ev.target).closest('.single_checkbox_option').after($.tmpl single_checkbox_template)

    set_checkbox_field_options:=>
	    checkbox_options_dict = {}
	    $('.single_checkbox_option').each (item)->
		    checkbox_options_dict[item]={}
		    checkbox_options_dict[item]['value']=$(this).find('.checkbox_input').val()
		    checkbox_options_dict[item]['label']={}
		    checkbox_options_dict[item]['lable'].value=$(this).find('.checkbox_input').val()
		    checkbox_options_dict[item]['lable'].id="label_#{item}"
		    checkbox_options_dict[item]['id']=item
		    checkbox_options_dict[item]['name']=item
	    field_settings_dict = @model.get('field_settings')
	    field_settings_dict[@model.get('current_field_edit')].checkboxes = checkbox_options_dict
	    @model.set field_settings:field_settings_dict
	    $('#'+ @model.get('current_field_edit')).html ''
	    for item, options of checkbox_options_dict
		    $('#' + @model.get('current_field_edit')).append("<span style='display:block'><input type='checkbox' value=''/> #{options.value}</span>")

    delete_single_checkbox_option:(ev)=>
	    $(ev.target).closest('.single_checkbox_option').remove()

    add_single_radiobox_option:(ev)=>
	    single_radiobox_template = $('#single_radiobox_option_template').template()
	    $(ev.target).closest('.single_radiobox_option').after($.tmpl single_radiobox_template)

    set_radiobox_field_options:=>
	    radiobox_options_dict = {}
	    $('.single_radiobox_option').each (item)->
		    radiobox_options_dict[item]={}
		    radiobox_options_dict[item]['value']=$(this).find('.radiobox_input').val()
		    radiobox_options_dict[item]['label']={}
		    radiobox_options_dict[item]['label'].value=$(this).find('.radiobox_input').val()
		    radiobox_options_dict[item]['label'].id="label_#{item}"
		    radiobox_options_dict[item]['id']=item
		    radiobox_options_dict[item]['name']=item
	    field_settings_dict = @model.get('field_settings')
	    field_settings_dict[@model.get('current_field_edit')].radioboxes = radiobox_options_dict
	    @model.set field_settings:field_settings_dict
	    $('#'+ @model.get('current_field_edit')).html ''
	    for item, options of radiobox_options_dict
		    $('#' + @model.get('current_field_edit')).append("<span style='display:block'><input type='radio' value=''/> #{options.value}</span>")

    delete_single_radiobox_option:(ev)=>
	    $(ev.target).closest('.single_radiobox_option').remove()

    change_tab:(ev)=>
	    $(ev.target).tab('show')

    add_input_item:(ev)=>
	    FormEvents.trigger(FormEvents.EVENT_TYPE_ADD_INPUT,$(ev.target).attr('type'))

    render:=>
	    $(@el).html $.tmpl(@template, @model.toJSON())

class TextInputTypeView extends Backbone.View
    initialize:=>
	    @template = $('#text_input_template').template()
	    @render()
    render:=>
	    $('form').append($.tmpl(@template, @model))

class TextAreaInputTypeView extends Backbone.View
    initialize:=>
	    @template = $('#textarea_input_template').template()
	    @render()
    render:=>
	    $('form').append($.tmpl(@template, @model))


class NumberInputTypeView extends Backbone.View
    initialize:=>
	    @template = $('#number_input_template').template()
	    @render()
    render:=>
	    $('form').append($.tmpl(@template, @model))

class SelectInputTypeView extends Backbone.View
    initialize:=>
	    @template = $('#select_input_template').template()
	    @render()
    render:=>
	    $('form').append($.tmpl(@template, @model))

class CheckboxInputTypeView extends Backbone.View
    initialize:=>
	    @template = $('#checkbox_input_template').template()
	    @render()
    render:=>
	    $('form').append($.tmpl(@template, @model))

class RadioboxInputTypeView extends Backbone.View
    initialize:=>
	    @template = $('#radiobox_input_template').template()
	    @render()
    render:=>
	    $('form').append($.tmpl(@template, @model))

class RootModel extends Backbone.Model
    initialize:=>

class RootController extends Backbone.Router
    initialize:(args)=>
	    @model = new RootModel
	    @model.set field_settings:{}
	    @model.set init_type:args['type']
	    if args.form_id != ''
		    @initialize_rendering_with_form(args.form_id)
	    else
		    @model.set form_title:'Untitled Form'
		    @model.set form_description:'This is my form, please fill it out. Its awesome.'
		    @model.set form_alignment:'form-vertical'
		    @model.set thankyou_message:'Thanks for filling this survey.'
		    @model.set num_fields:0
		    @initialize_rendering()
		    @render_left_panel()
		    FormEvents.bind(FormEvents.EVENT_TYPE_ADD_INPUT, @add_input_item, @)
		    FormEvents.bind(FormEvents.EVENT_TYPE_SAVE_FORM, @save_form, @)

    initialize_rendering:=>
	    @base_form_view = new BaseView model:@model, el:'#form_main_content'
	    @base_form_view.render()

    initialize_rendering_with_form:(form_id)=>
	    @base_form_view = new BaseView model:@model, el:'#form_main_content'
	    @base_form_view.render()
	    data_dict = {}
	    data_dict['form_id'] = form_id
	    success_function= (response)->
		    if response.error
			    alert('You need to signup before saving the form.')
		    else if response.success
			    @model.set form_title:response.field_dict.title
			    @model.set form_description: response.field_dict.description
			    @model.set form_alignment: response.field_dict.form_alignment
			    @model.set thankyou_message:response.field_dict.thankyou_message
			    @render_left_panel()
	    error_function= (obj, txt)->
		    alert "Error in retrieving form, please try again"
	    ajax_params =
		    url : '/edit_form'
		    type : 'POST'
		    context: @
		    data : data_dict
		    dataType : 'json'
		    success : success_function
		    error : error_function
	    $.ajax ajax_params

    render_left_panel:=>
	    @left_panel_view = new LeftPanelView model:@model, el:'#left_panel'

    set_field_settings:(input_type)=>
	    field_settings_dict = FieldSettings.get_field_defaults(input_type)
	    field_settings_dict['id'] = 'field' + @model.get('num_fields')
	    field_settings_dict['name'] = 'field' + @model.get('num_fields')
	    field_settings_dict['label']['id'] = 'Label' + @model.get('num_fields')
	    current_field_settings_dict = @model.get('field_settings')
	    current_field_settings_dict[field_settings_dict['id']] = field_settings_dict
	    @model.set field_settings:current_field_settings_dict

    add_input_item:(input_type)=>
	    @model.set num_fields:@model.get('num_fields') + 1
	    @set_field_settings(input_type)
	    @model_to_send = @model.get('field_settings')['field' + @model.get('num_fields')]
	    switch input_type
		    when 'text'
			    new_input_item = new TextInputTypeView model:@model_to_send
		    when 'textarea'
			    new_input_item = new TextAreaInputTypeView model:@model_to_send
		    when 'number'
			    new_input_item = new NumberInputTypeView model:@model_to_send
		    when 'select'
			    new_input_item = new SelectInputTypeView model:@model_to_send
		    when 'checkbox'
			    new_input_item = new CheckboxInputTypeView model:@model_to_send
		    when 'radiobox'
			    new_input_item = new RadioboxInputTypeView model:@model_to_send
		    else
			    return null

    save_form:=>
	    save_dict ={}
	    save_dict['title']= @model.get('form_title')
	    save_dict['description']=@model.get('form_description')
	    save_dict['field_dict']=@model.get('field_settings')
	    save_dict['form_alignment']=@model.get('form_alignment')
	    save_dict['thankyou_message'] = @model.get('thankyou_message')
	    count = 0
	    for id, settings of save_dict['field_dict']
		    count = count + 1
	    save_dict['count']= count
	    $('#form_main_content').mask('Saving Form..please wait...')
	    success_function= (response)->
		    $('#form_main_content').unmask()
		    if response.error
			    alert('You need to signup before saving the form.')
		    else if response.success
			    template = $('#success_form_message_template').template()
			    data = {}
			    data['form_id'] = response.form_id
			    $('#form_panel').append $.tmpl(template,data)
			    $('#save_form').addClass('disabled')
	    error_function= (obj, txt)->
		    $('#form_main_content').unmask()
		    alert "Error in saving form, please try again"
	    ajax_params =
		    url : '/create_form'
		    type : 'POST'
		    data : save_dict
		    dataType : 'json'
		    success : success_function
		    error : error_function
	    $.ajax ajax_params


root.RootController = RootController

initialize_form_builder=(type, form_id)->
    args = {}
    args['type'] = type
    args['form_id'] = form_id
    root_controller = new RootController args
root.initialize_form_builder = initialize_form_builder
