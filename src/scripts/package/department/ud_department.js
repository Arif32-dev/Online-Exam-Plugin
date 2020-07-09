import Admin_Base from './../../base/Admin_Base'
jQuery(document).ready(function($) {
    class UD_Department extends Admin_Base {
        constructor() {
            super();
            this.update_btn = $('.oe-update');
            this.delete_btn = $('.oe-delete');
            this.events();
        }
        events() {
            this.update_btn.on('click', this.handle_req.bind(this));
            this.delete_btn.on('click', this.handle_req.bind(this));
        }
        handle_req(e) {
            let fetch_data = {
                input_val: $(e.target).parent().parent().find('input').val(),
                last_update: $(e.currentTarget).attr('data-update-date'),
                id: $(e.currentTarget).attr('id'),
                action: $(e.currentTarget).attr('data-action')
            };
            this.ajax_req(e, $, file_url.ud_department_url, 'ud_department', fetch_data);
        }
    }
    new UD_Department();
})