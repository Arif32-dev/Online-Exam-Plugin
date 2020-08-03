import Admin_Base from './../../base/Admin_Base'
jQuery(document).ready(function($) {
    class UD_student extends Admin_Base {
        constructor() {
            super();
            this.update_btn = $('.oe-student-update');
            this.delete_btn = $('.oe-student-delete');
            this.restrict_btn = $('.oe-restrict-student');
            this.allow_btn = $('.oe-allow-student');
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
                std_id: $(e.currentTarget).attr('data-student-id'),
                std_name: $(e.currentTarget).parent().parent().find('input[name=teacher_name]').val(),
                std_dept: $(e.currentTarget).parent().parent().find('.teacher_dept').val(),
                std_phn: $(e.currentTarget).parent().parent().find('input[name=teacher_phn]').val(),
                restrict_date: $(e.currentTarget).attr('data-time'),
            }
            console.log(fetch_data);
            // this.ajax_req(e, $, file_url.ud_student_url, 'ud_student', fetch_data)
        }
    }
    new UD_student();
})