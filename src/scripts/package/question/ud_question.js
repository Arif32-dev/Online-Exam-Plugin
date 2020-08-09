import Admin_Base from './../../base/Admin_Base'
jQuery(document).ready(function($) {
    class UD_Question extends Admin_Base {
        constructor() {
            super();
            this.update_btn = $('.oe-folder-update');
            this.delete_btn = $('.oe-folder-delete');
            this.publish_btn = $('.oe-publish-qus');
            this.events();
        }
        events() {
            this.update_btn.on('click', this.handle_req.bind(this))
            this.delete_btn.on('click', this.handle_req.bind(this))
            this.publish_btn.on('click', this.handle_req.bind(this))
        }
        handle_req(e) {
            const fetch_data = {
                action: $(e.currentTarget).attr('data-action'),
                exam_folder_id: $(e.currentTarget).attr('data-exam-folder-id'),
                exam_folder_name: $(e.currentTarget).parent().parent().find('input[name=exam_folder_name]').val(),
                exam_time: $(e.currentTarget).attr('data-exam-time'),
                per_qus_mark: $(e.currentTarget).parent().parent().find('input[name=per_qus_mark]').val(),
                dept_id: $(e.currentTarget).parent().parent().find('.question_dept_id').val(),
                quantity: $(e.currentTarget).parent().parent().find('input[name=quantity]').val(),
                exam_time: $(e.currentTarget).parent().parent().find('input[name=exam_time]').val(),
                pass_percentage: $(e.currentTarget).parent().parent().find('input[name=pass_percentage]').val(),
            }
            this.ajax_req(e, $, file_url.ud_question_url, 'ud_question', fetch_data)
        }
    }
    new UD_Question();
})