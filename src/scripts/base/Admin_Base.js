class Admin_Base {
    constructor() {
        this.notification;
    }

    ajax_req(e, $, url, ajax_action, fetch_data) {
        $.ajax({
            url: url,
            data: fetch_data,
            type: 'post',
            success: res => {
                console.log(res)
                this.status_check(e, $, res, ajax_action);
            }
        })
    }
    status_check(e, $, res, ajax_action) {
        if (ajax_action == 'ud_student') {
            this.teacher_and_std_msg(res, $, 'student', e);
        }
        if (ajax_action == 'ud_department') {
            this.common_msg(e, res, $);
        }
        if (ajax_action == 'ud_routine') {
            this.common_msg(e, res, $);
        }
        if (ajax_action == 'ud_teacher') {
            this.teacher_and_std_msg(res, $, 'teacher', e);
        }

        if (ajax_action == 'ud_question') {
            if (res == 'empty_dept') {
                let text = 'Please select a department';
                this.output('error', text, $);
            }
            if (res == 'empty_field') {
                let text = 'One or more field is empty';
                this.output('warning', text, $);
            }
            if (res == 'prev_exam_exists') {
                let text = 'Please terminate previous exam to publish new exam';
                this.output('warning', text, $);
            }
            if (res == 'terminated') {
                let text = 'Exam terminated successfully';
                this.output('success', text, $);
                $(e.currentTarget).attr('disabled', true);
                $(e.currentTarget).removeClass('ow-yellow-active');
                $(e.currentTarget).addClass('oe-yellow');
                $(e.currentTarget).parent().parent().find('.exam_status').html('Finished');
            }
            if (res == 'published') {
                let text = 'Exam published sucessfully';
                this.output('success', text, $);
                $(e.currentTarget).parent().parent().find('.oe-terminate').addClass('ow-yellow-active');
                $(e.currentTarget).parent().parent().find('.oe-terminate').attr('disabled', false);
                $(e.currentTarget).parent().parent().find('.exam_status').html('Running');
                $(e.currentTarget).parent().parent().find('.question_dept_id').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=exam_folder_name]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=quantity]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=exam_time]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=pass_percentage]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=per_qus_mark]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('.oe-folder-update').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('.oe-folder-update').addClass('update_disabled');
                $(e.currentTarget).parent().html("<span class='user-status user_inactive'>Published</span>");
            }
            if (res == 'not_published') {
                let text = 'Exam not published';
                this.output('warning', text, $);
            }
            this.common_msg(e, res, $);
        }

    }
    common_msg(e, res, $) {
        if (res == 'updated') {
            let text = 'Updated Successfully!';
            this.output('success', text, $);
        }
        if (res == 'not_updated') {
            let text = 'Nothing changed to update!';
            this.output('warning', text, $);
        }
        if (res == 'failed') {
            let text = 'Something went wrong!';
            this.output('error', text, $);
        }
        if (res == 'deleted') {
            let text = 'Deleted Successfully!';
            this.output('success', text, $);
            $(e.currentTarget).parent().parent().fadeOut();
        }
    }
    teacher_and_std_msg(res, $, role, e) {
        if (res == 'updated' || res == 'allowed') {
            let text = (res == 'allowed' ? 'User allowed as a ' + role + '' : 'Updated Successfully!');
            this.output('success', text, $);
        }
        if (res == 'not_updated' || res == 'restricted') {
            let text = (res == 'restricted' ? 'User restricted successfully' : 'Nothing changed to update!');
            this.output('warning', text, $);
        }
        if (res == 'user_not_updated') {
            let text = 'User not updated! Please try again';
            this.output('warning', text, $);
        }
        if (res == 'empty_dept') {
            let text = 'Please select a department';
            this.output('error', text, $);
        }
        if (res == 'deleted') {
            let text = 'Deleted Successfully!';
            this.output('success', text, $);
            $(e.currentTarget).parent().parent().fadeOut();
        }
        if (res == 'user_not_deleted') {
            let text = 'Something went wrong in deleting ' + role + '!';
            this.output('error', text, $);
        }
        if (res == 'failed') {
            let text = 'Something went wrong!';
            this.output('error', text, $);
        }
        if (res == 'allowed' || res == 'restricted') {
            setTimeout(() => {
                location.reload();
            }, 2500);
        }
    }
    output(class_text, text, $) {
        this.notification = $('.oe-notification');
        this.notification.html(`
                    <div class="notice notice-${class_text} is-dismissible">
                        <p> ${text} </p> 
                    </div>
                `).hide().slideDown();

        setTimeout(() => {
            this.notification.slideUp();
        }, 2500);
    }
}
export default Admin_Base;