import Admin_Base from './../../base/Admin_Base'
jQuery(document).ready(function($) {
    class UD_Teacher extends Admin_Base {
        constructor() {
            super();
            this.update_btn = $('.oe-teacher-update');
            this.delete_btn = $('.oe-teacher-delete');
            this.restrict_btn = $('.oe-restrict-teacher');
            this.allow_btn = $('.oe-allow-teacher');
            this.events();
        }
        events() {
            this.update_btn.on('click', this.handle_req.bind(this))
            this.delete_btn.on('click', this.handle_req.bind(this))
            this.restrict_btn.on('click', this.handle_req.bind(this))
            this.allow_btn.on('click', this.handle_req.bind(this))
        }
        handle_req(e) {
            const fetch_data = {
                action: $(e.currentTarget).attr('data-action'),
                teacher_id: $(e.currentTarget).attr('data-teacher-id'),
                teacher_name: $(e.currentTarget).parent().parent().find('input[name=teacher_name]').val(),
                teacher_dept: $(e.currentTarget).parent().parent().find('.teacher_dept').val(),
                teacher_phn: $(e.currentTarget).parent().parent().find('input[name=teacher_phn]').val(),
                restrict_date: $(e.currentTarget).attr('data-time'),
            }
            this.ajax_req(e, $, file_url.ud_teacher_url, 'ud_teacher', fetch_data)
        }
    }
    new UD_Teacher();
})