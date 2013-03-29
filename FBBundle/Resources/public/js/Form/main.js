(function() {
  var BaseView, CheckboxInputTypeView, FieldSettings, FieldSettingsView, FormEvents, LeftPanelView, NumberInputTypeView, RadioboxInputTypeView, RootController, RootModel, SelectInputTypeView, TextAreaInputTypeView, TextInputTypeView, initialize_form_builder, root,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  FormEvents = {
    EVENT_TYPE_ADD_INPUT: 'add_input_item',
    EVENT_TYPE_SAVE_FORM: 'save_form'
  };

  _.extend(FormEvents, Backbone.Events);

  FieldSettings = (function() {

    function FieldSettings() {}

    FieldSettings.get_field_defaults = function(type) {
      switch (type) {
        case 'text':
          return {
            id: '',
            type: 'text',
            label: {
              id: '',
              value: 'Single Line Text Input'
            },
            hidden: false,
            maxlength: ''
          };
        case 'number':
          return {
            id: '',
            type: 'number',
            label: {
              id: '',
              value: 'Number'
            },
            hidden: false,
            maxlength: ''
          };
        case 'textarea':
          return {
            id: '',
            type: 'textarea',
            label: {
              id: '',
              value: 'Paragraph Text Input'
            },
            hidden: false
          };
        case 'select':
          return {
            id: '',
            type: 'select',
            options: {
              option1: {
                id: 'option1',
                name: 'option1',
                value: 'Option1'
              },
              option2: {
                id: 'option2',
                name: 'option2',
                value: 'Option2'
              },
              option3: {
                id: 'option3',
                name: 'option3',
                value: 'Option3'
              }
            },
            label: {
              id: '',
              value: 'Select Field'
            },
            hidden: false
          };
        case 'radiobox':
          return {
            id: '',
            name: '',
            type: 'radioboxgroup',
            radioboxes: {
              option1: {
                id: 'option1',
                name: 'option1',
                value: 'Option1',
                label: {
                  id: 'rdo_label1',
                  value: 'Option1'
                }
              },
              option2: {
                id: 'option2',
                name: 'option2',
                value: 'Option2',
                label: {
                  id: 'rdo_label2',
                  value: 'Option2'
                }
              },
              option3: {
                id: 'option3',
                name: 'option3',
                value: 'Option3',
                label: {
                  id: 'rdo_label3',
                  value: 'Option3'
                }
              }
            },
            label: {
              id: '',
              value: 'Single Option Select'
            },
            hidden: false
          };
        case 'checkbox':
          return {
            id: '',
            type: 'checkboxgroup',
            name: '',
            checkboxes: {
              option1: {
                id: 'option1',
                name: 'option1',
                value: 'Option1',
                label: {
                  id: 'chk_label1',
                  value: 'Option1'
                }
              },
              option2: {
                id: 'option2',
                name: 'option2',
                value: 'Option2',
                label: {
                  id: 'chk_label2',
                  value: 'Option2'
                }
              },
              option3: {
                id: 'option3',
                name: 'option3',
                value: 'Option3',
                label: {
                  id: 'chk_label3',
                  value: 'Option3'
                }
              }
            },
            label: {
              id: '',
              value: 'Multiple Select'
            },
            hidden: false
          };
        default:
          return null;
      }
    };

    return FieldSettings;

  })();

  BaseView = (function(_super) {

    __extends(BaseView, _super);

    function BaseView() {
      this.save_form_click = __bind(this.save_form_click, this);
      this.tab_field_setting = __bind(this.tab_field_setting, this);
      this.input_element_settings = __bind(this.input_element_settings, this);
      this.remove_input_element = __bind(this.remove_input_element, this);
      this.hide_action_buttons = __bind(this.hide_action_buttons, this);
      this.show_action_buttons = __bind(this.show_action_buttons, this);
      this.change_form_description = __bind(this.change_form_description, this);
      this.change_form_title = __bind(this.change_form_title, this);
      this.label_align_change = __bind(this.label_align_change, this);
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      BaseView.__super__.constructor.apply(this, arguments);
    }

    BaseView.prototype.initialize = function() {
      this.model.set({
        label_position: 'top'
      });
      return this.template = $('#form_base').template();
    };

    BaseView.prototype.render = function() {
      return $(this.el).html($.tmpl(this.template, this.model.toJSON()));
    };

    BaseView.prototype.events = {
      'click .remove_item': 'remove_input_element',
      'click .input_element': 'input_element_settings',
      'mouseenter .input_element': 'show_action_buttons',
      'mouseleave .input_element': 'hide_action_buttons',
      'keyup #form_title_text': 'change_form_title',
      'keyup #form_description_text': 'change_form_description',
      'click .label_align_change': 'label_align_change',
      'click #field_setting_tab': 'tab_field_setting',
      'click #save_form': 'save_form_click'
    };

    BaseView.prototype.label_align_change = function(ev) {
      if ($(ev.target).attr('value') === 'left') {
        $('#form_panel').find('form').removeClass('form-vertical').addClass('form-horizontal');
        return this.model.set({
          form_alignment: 'form-horizontal'
        });
      } else {
        $('#form_panel').find('form').removeClass('form-horizontal').addClass('form-vertical');
        return this.model.set({
          form_alignment: 'form-vertical'
        });
      }
    };

    BaseView.prototype.change_form_title = function() {
      this.model.set({
        form_title: $('#form_title_text').val()
      });
      return $('#form_title').html($('#form_title_text').val());
    };

    BaseView.prototype.change_form_description = function() {
      this.model.set({
        form_description: $('#form_description_text').val()
      });
      return $('#form_description').html($('#form_description_text').val());
    };

    BaseView.prototype.show_action_buttons = function(ev) {
      return $(ev.target).find('.action_items').removeClass('hide');
    };

    BaseView.prototype.hide_action_buttons = function(ev) {
      return $(ev.target).find('.action_items').addClass('hide');
    };

    BaseView.prototype.remove_input_element = function(ev) {
      var field_settings_dict;
      field_settings_dict = this.model.get('field_settings');
      delete field_settings_dict[$(ev.target).closest('.input_element').find('.controls').children(':first').attr('id')];
      this.model.set({
        field_settings: field_settings_dict
      });
      return $(ev.target).closest('.input_element').remove();
    };

    BaseView.prototype.input_element_settings = function(ev) {
      if ($(ev.target).is('.remove_item')) return;
      this.model.set({
        current_field_edit: $(ev.target).closest('.input_element').find('.controls').children(':first').attr('id')
      });
      $('.input_element').each(function(item) {
        return $(this).removeClass('selected');
      });
      $(ev.target).closest('.input_element').addClass('selected');
      if (this.render_field_settings) this.render_field_settings.detach();
      this.render_field_settings = new FieldSettingsView({
        el: '#tab3',
        model: this.model,
        type: $(ev.target).closest('.input_element').attr('type'),
        field_id: $(ev.target).closest('.input_element').find('.controls').children(':first').attr('id')
      });
      return $('#selection_tabs a:last').tab('show');
    };

    BaseView.prototype.tab_field_setting = function() {
      var a;
      return a = $('#form_panel').find('.input_element:first');
    };

    BaseView.prototype.save_form_click = function() {
      return FormEvents.trigger(FormEvents.EVENT_TYPE_SAVE_FORM);
    };

    return BaseView;

  })(Backbone.View);

  FieldSettingsView = (function(_super) {

    __extends(FieldSettingsView, _super);

    function FieldSettingsView() {
      this.detach = __bind(this.detach, this);
      this.render = __bind(this.render, this);
      this.get_template = __bind(this.get_template, this);
      this.change_max_chars = __bind(this.change_max_chars, this);
      this.change_field_hide_status = __bind(this.change_field_hide_status, this);
      this.change_field_label_text = __bind(this.change_field_label_text, this);
      this.initialize = __bind(this.initialize, this);
      FieldSettingsView.__super__.constructor.apply(this, arguments);
    }

    FieldSettingsView.prototype.initialize = function() {
      var template_render;
      template_render = this.get_template(this.options.type);
      this.template = $(template_render).template();
      return this.render();
    };

    FieldSettingsView.prototype.events = {
      'keyup .field_label_text': 'change_field_label_text',
      'change .hide_field_checkbox': 'change_field_hide_status',
      'change .field_max_chars': 'change_max_chars'
    };

    FieldSettingsView.prototype.change_field_label_text = function() {
      var field_settings_dict;
      $('#form_panel').find('#' + this.options.field_id).closest('.control-group').find('.control-label').html($('.field_setting').find('.field_label_text:first').val());
      field_settings_dict = this.model.get('field_settings');
      field_settings_dict[this.options.field_id].label.value = $('.field_setting').find('.field_label_text').val();
      return this.model.set({
        field_settings: field_settings_dict
      });
    };

    FieldSettingsView.prototype.change_field_hide_status = function(ev) {
      var field_settings_dict;
      field_settings_dict = this.model.get('field_settings');
      if ($(ev.target).is(':checked')) {
        field_settings_dict[this.options.field_id].hidden = true;
      } else {
        field_settings_dict[this.options.field_id].hidden = false;
      }
      return this.model.set({
        field_settings: field_settings_dict
      });
    };

    FieldSettingsView.prototype.change_max_chars = function() {
      var field_settings_dict;
      field_settings_dict = this.model.get('field_settings');
      field_settings_dict[this.options.field_id].maxlength = $('.field_setting').find('.maxlen').val();
      return this.model.set({
        field_settings: field_settings_dict
      });
    };

    FieldSettingsView.prototype.get_template = function(type) {
      switch (type) {
        case 'text':
          return '#text_settings_template';
        case 'textarea':
          return '#textarea_settings_template';
        case 'select':
          return '#select_settings_template';
        case 'number':
          return '#number_settings_template';
        case 'radiobox':
          return '#radiobox_settings_template';
        case 'checkbox':
          return '#checkbox_settings_template';
        default:
          return None;
      }
    };

    FieldSettingsView.prototype.render = function() {
      var field_settings;
      field_settings = this.model.get('field_settings')[this.options.field_id];
      $(this.el).html('');
      return $(this.el).html($.tmpl(this.template, field_settings));
    };

    FieldSettingsView.prototype.detach = function() {
      $(this.el).unbind();
      return this.model.unbind();
    };

    return FieldSettingsView;

  })(Backbone.View);

  LeftPanelView = (function(_super) {

    __extends(LeftPanelView, _super);

    function LeftPanelView() {
      this.render = __bind(this.render, this);
      this.add_input_item = __bind(this.add_input_item, this);
      this.change_tab = __bind(this.change_tab, this);
      this.delete_single_radiobox_option = __bind(this.delete_single_radiobox_option, this);
      this.set_radiobox_field_options = __bind(this.set_radiobox_field_options, this);
      this.add_single_radiobox_option = __bind(this.add_single_radiobox_option, this);
      this.delete_single_checkbox_option = __bind(this.delete_single_checkbox_option, this);
      this.set_checkbox_field_options = __bind(this.set_checkbox_field_options, this);
      this.add_single_checkbox_option = __bind(this.add_single_checkbox_option, this);
      this.delete_single_select_option = __bind(this.delete_single_select_option, this);
      this.set_select_field_options = __bind(this.set_select_field_options, this);
      this.add_single_select_option = __bind(this.add_single_select_option, this);
      this.initialize = __bind(this.initialize, this);
      LeftPanelView.__super__.constructor.apply(this, arguments);
    }

    LeftPanelView.prototype.initialize = function() {
      this.template = $('#left_panel_template').template();
      return this.render();
    };

    LeftPanelView.prototype.events = {
      'click a': 'change_tab',
      'click .input_item': 'add_input_item',
      'click .add_single_select_option': 'add_single_select_option',
      'click .delete_single_select_option': 'delete_single_select_option',
      'click #select_options_done': 'set_select_field_options',
      'click .add_single_checkbox_option': 'add_single_checkbox_option',
      'click .delete_single_checkbox_option': 'delete_single_checkbox_option',
      'click #checkbox_options_done': 'set_checkbox_field_options',
      'click .add_single_radiobox_option': 'add_single_radiobox_option',
      'click .delete_single_radiobox_option': 'delete_single_radiobox_option',
      'click #radiobox_options_done': 'set_radiobox_field_options'
    };

    LeftPanelView.prototype.add_single_select_option = function(ev) {
      var single_select_template;
      single_select_template = $('#single_select_option_template').template();
      return $(ev.target).closest('.single_select_option').after($.tmpl(single_select_template));
    };

    LeftPanelView.prototype.set_select_field_options = function() {
      var field_settings_dict, item, options, select_options_dict, _results;
      select_options_dict = {};
      $('.single_select_option').each(function(item) {
        select_options_dict[item]['id'] = item;
        return select_options_dict[item]['name'] = item;
      });
      field_settings_dict = this.model.get('field_settings');
      field_settings_dict[this.model.get('current_field_edit')].options = select_options_dict;
      this.model.set({
        field_settings: field_settings_dict
      });
      $('#' + this.model.get('current_field_edit')).html('');
      _results = [];
      for (item in select_options_dict) {
        options = select_options_dict[item];
        _results.push($('#' + this.model.get('current_field_edit')).append("<option>" + options.value + "</option>"));
      }
      return _results;
    };

    LeftPanelView.prototype.delete_single_select_option = function(ev) {
      return $(ev.target).closest('.single_select_option').remove();
    };

    LeftPanelView.prototype.add_single_checkbox_option = function(ev) {
      var single_checkbox_template;
      single_checkbox_template = $('#single_checkbox_option_template').template();
      return $(ev.target).closest('.single_checkbox_option').after($.tmpl(single_checkbox_template));
    };

    LeftPanelView.prototype.set_checkbox_field_options = function() {
      var checkbox_options_dict, field_settings_dict, item, options, _results;
      checkbox_options_dict = {};
      $('.single_checkbox_option').each(function(item) {
        checkbox_options_dict[item] = {};
        checkbox_options_dict[item]['value'] = $(this).find('.checkbox_input').val();
        checkbox_options_dict[item]['label'] = {};
        checkbox_options_dict[item]['lable'].value = $(this).find('.checkbox_input').val();
        checkbox_options_dict[item]['lable'].id = "label_" + item;
        checkbox_options_dict[item]['id'] = item;
        return checkbox_options_dict[item]['name'] = item;
      });
      field_settings_dict = this.model.get('field_settings');
      field_settings_dict[this.model.get('current_field_edit')].checkboxes = checkbox_options_dict;
      this.model.set({
        field_settings: field_settings_dict
      });
      $('#' + this.model.get('current_field_edit')).html('');
      _results = [];
      for (item in checkbox_options_dict) {
        options = checkbox_options_dict[item];
        _results.push($('#' + this.model.get('current_field_edit')).append("<span style='display:block'><input type='checkbox' value=''/> " + options.value + "</span>"));
      }
      return _results;
    };

    LeftPanelView.prototype.delete_single_checkbox_option = function(ev) {
      return $(ev.target).closest('.single_checkbox_option').remove();
    };

    LeftPanelView.prototype.add_single_radiobox_option = function(ev) {
      var single_radiobox_template;
      single_radiobox_template = $('#single_radiobox_option_template').template();
      return $(ev.target).closest('.single_radiobox_option').after($.tmpl(single_radiobox_template));
    };

    LeftPanelView.prototype.set_radiobox_field_options = function() {
      var field_settings_dict, item, options, radiobox_options_dict, _results;
      radiobox_options_dict = {};
      $('.single_radiobox_option').each(function(item) {
        radiobox_options_dict[item] = {};
        radiobox_options_dict[item]['value'] = $(this).find('.radiobox_input').val();
        radiobox_options_dict[item]['label'] = {};
        radiobox_options_dict[item]['label'].value = $(this).find('.radiobox_input').val();
        radiobox_options_dict[item]['label'].id = "label_" + item;
        radiobox_options_dict[item]['id'] = item;
        return radiobox_options_dict[item]['name'] = item;
      });
      field_settings_dict = this.model.get('field_settings');
      field_settings_dict[this.model.get('current_field_edit')].radioboxes = radiobox_options_dict;
      this.model.set({
        field_settings: field_settings_dict
      });
      $('#' + this.model.get('current_field_edit')).html('');
      _results = [];
      for (item in radiobox_options_dict) {
        options = radiobox_options_dict[item];
        _results.push($('#' + this.model.get('current_field_edit')).append("<span style='display:block'><input type='radio' value=''/> " + options.value + "</span>"));
      }
      return _results;
    };

    LeftPanelView.prototype.delete_single_radiobox_option = function(ev) {
      return $(ev.target).closest('.single_radiobox_option').remove();
    };

    LeftPanelView.prototype.change_tab = function(ev) {
      return $(ev.target).tab('show');
    };

    LeftPanelView.prototype.add_input_item = function(ev) {
      return FormEvents.trigger(FormEvents.EVENT_TYPE_ADD_INPUT, $(ev.target).attr('type'));
    };

    LeftPanelView.prototype.render = function() {
      return $(this.el).html($.tmpl(this.template, this.model.toJSON()));
    };

    return LeftPanelView;

  })(Backbone.View);

  TextInputTypeView = (function(_super) {

    __extends(TextInputTypeView, _super);

    function TextInputTypeView() {
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      TextInputTypeView.__super__.constructor.apply(this, arguments);
    }

    TextInputTypeView.prototype.initialize = function() {
      this.template = $('#text_input_template').template();
      return this.render();
    };

    TextInputTypeView.prototype.render = function() {
      return $('form').append($.tmpl(this.template, this.model));
    };

    return TextInputTypeView;

  })(Backbone.View);

  TextAreaInputTypeView = (function(_super) {

    __extends(TextAreaInputTypeView, _super);

    function TextAreaInputTypeView() {
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      TextAreaInputTypeView.__super__.constructor.apply(this, arguments);
    }

    TextAreaInputTypeView.prototype.initialize = function() {
      this.template = $('#textarea_input_template').template();
      return this.render();
    };

    TextAreaInputTypeView.prototype.render = function() {
      return $('form').append($.tmpl(this.template, this.model));
    };

    return TextAreaInputTypeView;

  })(Backbone.View);

  NumberInputTypeView = (function(_super) {

    __extends(NumberInputTypeView, _super);

    function NumberInputTypeView() {
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      NumberInputTypeView.__super__.constructor.apply(this, arguments);
    }

    NumberInputTypeView.prototype.initialize = function() {
      this.template = $('#number_input_template').template();
      return this.render();
    };

    NumberInputTypeView.prototype.render = function() {
      return $('form').append($.tmpl(this.template, this.model));
    };

    return NumberInputTypeView;

  })(Backbone.View);

  SelectInputTypeView = (function(_super) {

    __extends(SelectInputTypeView, _super);

    function SelectInputTypeView() {
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      SelectInputTypeView.__super__.constructor.apply(this, arguments);
    }

    SelectInputTypeView.prototype.initialize = function() {
      this.template = $('#select_input_template').template();
      return this.render();
    };

    SelectInputTypeView.prototype.render = function() {
      return $('form').append($.tmpl(this.template, this.model));
    };

    return SelectInputTypeView;

  })(Backbone.View);

  CheckboxInputTypeView = (function(_super) {

    __extends(CheckboxInputTypeView, _super);

    function CheckboxInputTypeView() {
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      CheckboxInputTypeView.__super__.constructor.apply(this, arguments);
    }

    CheckboxInputTypeView.prototype.initialize = function() {
      this.template = $('#checkbox_input_template').template();
      return this.render();
    };

    CheckboxInputTypeView.prototype.render = function() {
      return $('form').append($.tmpl(this.template, this.model));
    };

    return CheckboxInputTypeView;

  })(Backbone.View);

  RadioboxInputTypeView = (function(_super) {

    __extends(RadioboxInputTypeView, _super);

    function RadioboxInputTypeView() {
      this.render = __bind(this.render, this);
      this.initialize = __bind(this.initialize, this);
      RadioboxInputTypeView.__super__.constructor.apply(this, arguments);
    }

    RadioboxInputTypeView.prototype.initialize = function() {
      this.template = $('#radiobox_input_template').template();
      return this.render();
    };

    RadioboxInputTypeView.prototype.render = function() {
      return $('form').append($.tmpl(this.template, this.model));
    };

    return RadioboxInputTypeView;

  })(Backbone.View);

  RootModel = (function(_super) {

    __extends(RootModel, _super);

    function RootModel() {
      this.initialize = __bind(this.initialize, this);
      RootModel.__super__.constructor.apply(this, arguments);
    }

    RootModel.prototype.initialize = function() {};

    return RootModel;

  })(Backbone.Model);

  RootController = (function(_super) {

    __extends(RootController, _super);

    function RootController() {
      this.save_form = __bind(this.save_form, this);
      this.add_input_item = __bind(this.add_input_item, this);
      this.set_field_settings = __bind(this.set_field_settings, this);
      this.render_left_panel = __bind(this.render_left_panel, this);
      this.initialize_rendering_with_form = __bind(this.initialize_rendering_with_form, this);
      this.initialize_rendering = __bind(this.initialize_rendering, this);
      this.initialize = __bind(this.initialize, this);
      RootController.__super__.constructor.apply(this, arguments);
    }

    RootController.prototype.initialize = function(args) {
      this.model = new RootModel;
      this.model.set({
        field_settings: {}
      });
      this.model.set({
        init_type: args['type']
      });
      if (args.form_id !== '') {
        return this.initialize_rendering_with_form(args.form_id);
      } else {
        this.model.set({
          form_title: 'Untitled Form'
        });
        this.model.set({
          form_description: 'This is my form, please fill it out. Its awesome.'
        });
        this.model.set({
          form_alignment: 'form-vertical'
        });
        this.model.set({
          thankyou_message: 'Thanks for filling this survey.'
        });
        this.model.set({
          num_fields: 0
        });
        this.initialize_rendering();
        this.render_left_panel();
        FormEvents.bind(FormEvents.EVENT_TYPE_ADD_INPUT, this.add_input_item, this);
        return FormEvents.bind(FormEvents.EVENT_TYPE_SAVE_FORM, this.save_form, this);
      }
    };

    RootController.prototype.initialize_rendering = function() {
      this.base_form_view = new BaseView({
        model: this.model,
        el: '#form_main_content'
      });
      return this.base_form_view.render();
    };

    RootController.prototype.initialize_rendering_with_form = function(form_id) {
      var ajax_params, data_dict, error_function, success_function;
      this.base_form_view = new BaseView({
        model: this.model,
        el: '#form_main_content'
      });
      this.base_form_view.render();
      data_dict = {};
      data_dict['form_id'] = form_id;
      success_function = function(response) {
        if (response.error) {
          return alert('You need to signup before saving the form.');
        } else if (response.success) {
          this.model.set({
            form_title: response.field_dict.title
          });
          this.model.set({
            form_description: response.field_dict.description
          });
          this.model.set({
            form_alignment: response.field_dict.form_alignment
          });
          this.model.set({
            thankyou_message: response.field_dict.thankyou_message
          });
          return this.render_left_panel();
        }
      };
      error_function = function(obj, txt) {
        return alert("Error in retrieving form, please try again");
      };
      ajax_params = {
        url: '/edit_form',
        type: 'POST',
        context: this,
        data: data_dict,
        dataType: 'json',
        success: success_function,
        error: error_function
      };
      return $.ajax(ajax_params);
    };

    RootController.prototype.render_left_panel = function() {
      return this.left_panel_view = new LeftPanelView({
        model: this.model,
        el: '#left_panel'
      });
    };

    RootController.prototype.set_field_settings = function(input_type) {
      var current_field_settings_dict, field_settings_dict;
      field_settings_dict = FieldSettings.get_field_defaults(input_type);
      field_settings_dict['id'] = 'field' + this.model.get('num_fields');
      field_settings_dict['name'] = 'field' + this.model.get('num_fields');
      field_settings_dict['label']['id'] = 'Label' + this.model.get('num_fields');
      current_field_settings_dict = this.model.get('field_settings');
      current_field_settings_dict[field_settings_dict['id']] = field_settings_dict;
      return this.model.set({
        field_settings: current_field_settings_dict
      });
    };

    RootController.prototype.add_input_item = function(input_type) {
      var new_input_item;
      this.model.set({
        num_fields: this.model.get('num_fields') + 1
      });
      this.set_field_settings(input_type);
      this.model_to_send = this.model.get('field_settings')['field' + this.model.get('num_fields')];
      switch (input_type) {
        case 'text':
          return new_input_item = new TextInputTypeView({
            model: this.model_to_send
          });
        case 'textarea':
          return new_input_item = new TextAreaInputTypeView({
            model: this.model_to_send
          });
        case 'number':
          return new_input_item = new NumberInputTypeView({
            model: this.model_to_send
          });
        case 'select':
          return new_input_item = new SelectInputTypeView({
            model: this.model_to_send
          });
        case 'checkbox':
          return new_input_item = new CheckboxInputTypeView({
            model: this.model_to_send
          });
        case 'radiobox':
          return new_input_item = new RadioboxInputTypeView({
            model: this.model_to_send
          });
        default:
          return null;
      }
    };

    RootController.prototype.save_form = function() {
      var ajax_params, count, error_function, id, save_dict, settings, success_function, _ref;
      save_dict = {};
      save_dict['title'] = this.model.get('form_title');
      save_dict['description'] = this.model.get('form_description');
      save_dict['field_dict'] = this.model.get('field_settings');
      save_dict['form_alignment'] = this.model.get('form_alignment');
      save_dict['thankyou_message'] = this.model.get('thankyou_message');
      count = 0;
      _ref = save_dict['field_dict'];
      for (id in _ref) {
        settings = _ref[id];
        count = count + 1;
      }
      save_dict['count'] = count;
      $('#form_main_content').mask('Saving Form..please wait...');
      success_function = function(response) {
        var data, template;
        $('#form_main_content').unmask();
        if (response.error) {
          return alert('You need to signup before saving the form.');
        } else if (response.success) {
          template = $('#success_form_message_template').template();
          data = {};
          data['form_id'] = response.form_id;
          $('#form_panel').append($.tmpl(template, data));
          return $('#save_form').addClass('disabled');
        }
      };
      error_function = function(obj, txt) {
        $('#form_main_content').unmask();
        return alert("Error in saving form, please try again");
      };
      ajax_params = {
        url: '/create_form',
        type: 'POST',
        data: save_dict,
        dataType: 'json',
        success: success_function,
        error: error_function
      };
      return $.ajax(ajax_params);
    };

    return RootController;

  })(Backbone.Router);

  root.RootController = RootController;

  initialize_form_builder = function(type, form_id) {
    var args, root_controller;
    args = {};
    args['type'] = type;
    args['form_id'] = form_id;
    return root_controller = new RootController(args);
  };

  root.initialize_form_builder = initialize_form_builder;

}).call(this);
