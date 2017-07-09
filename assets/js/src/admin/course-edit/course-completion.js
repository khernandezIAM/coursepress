/* global CoursePress */

(function() {
    'use strict';

    CoursePress.Define( 'CourseCompletion', function( $, doc, win ) {
        return CoursePress.View.extend({
            template_id: 'coursepress-course-completion-tpl',
            el: $('#course-completion'),
            courseEditor: false,
            current: 'pre_completion',
            events: {
                'change [name="meta_basic_certificate"]': 'toggleSetting',
                'change [name]': 'updateModel',
                'click .cp-select-list li': 'switchCompletionPage',
                'focus [name]': 'removeErrorMarker'
            },
            initialize: function(model, EditCourse) {
                this.model = model;
                this.courseEditor = EditCourse;
                EditCourse.on('coursepress:validate-course-completion', this.validate, this);

                this.on( 'view_rendered', this.setUpUI, this );

                this.render();
            },
            toggleSetting: function(ev) {
                var sender = $(ev.currentTarget),
                    is_checked = sender.is(':checked'),
                    container = this.$('#custom-certificate-setting');

                container[ is_checked ? 'slideDown' : 'slideUp' ]();
            },
            setUpUI: function() {
                this.background = new CoursePress.AddImage( this.$('[name="meta_certificate_background"]') );
                this.$('select').select2();
                this.$('.switch-tmce').trigger('click');

                this.the_title = this.$('#page-completion-title');
                this.the_content = this.$('#page-completion-content');
            },
            switchCompletionPage: function( ev ) {
                var sender, page, title, description, the_page;

                sender = this.$(ev.currentTarget);
                page = sender.data('page');
                title = this.$('#completion-title');
                description = this.$('#completion-description');

                sender.siblings().removeClass('active');
                sender.addClass('active');

                if ( ( the_page = win._coursepress.completion_pages[page] ) ) {
                    title.html( the_page.title );
                    description.html( the_page.description );

                    this.the_title.val( this.model.get( page + '_title' ) );
                    this.the_content.val( this.model.get( page + '_content' ) );
                }
            },
            validate: function() {
                var proceed = true;

                if ( ! this.the_title.val() ) {
                    this.the_title.parent().addClass('cp-error');
                    proceed = false;
                }

                if ( ! proceed ) {
                    this.courseEditor.goToNext = false;
                    return false;
                }

                this.courseEditor.updateCourse();
            },
            updateModel: function( ev ) {
                var input, name, type, value, first, model;

                input = $(ev.currentTarget);
                name = input.attr('name');

                if ( ( type = input.attr('type') ) &&
                    _.contains(['checkbox', 'radio'], type ) ) {
                    value = input.is(':checked') ? input.val() : false;
                } else {
                    value = input.val();
                }

                name = name.split('.');
                first = name.shift();
                model = this.model.get( first );

                if ( name.length ) {
                    _.each(name, function (t) {
                        model[t] = value;
                    }, this);
                    this.model.set( first, model );
                } else {
                    this.model.set( first, value );
                }
            }
        });
    });
})();