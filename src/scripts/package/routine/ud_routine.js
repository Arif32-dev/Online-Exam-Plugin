import Admin_Base from './../../base/Admin_Base'
jQuery(document).ready(function($) {
    class UD_routine extends Admin_Base {
        constructor() {
            super();
            this.update_btn = $('.oe-routine-update');
            this.delete_btn = $('.oe-routine-delete');
            this.events();
        }
        events() {
            this.update_btn.on('click', this.handle_req.bind(this));
            this.delete_btn.on('click', this.handle_req.bind(this));
        }
        handle_req(e) {
            let fetch_data = {
                exam_name: $(e.target).parent().parent().find('.exam_name').val(),
                exam_date: $(e.target).parent().parent().find('.exam_date').val(),
                id: $(e.currentTarget).attr('data-id'),
                dept_id: $(e.target).parent().parent().find('.dept_id').val(),
                action: $(e.currentTarget).attr('data-action')
            };
            this.ajax_req(e, $, file_url.ud_routine_url, 'ud_routine', fetch_data);
        }
    }
    new UD_routine();
})