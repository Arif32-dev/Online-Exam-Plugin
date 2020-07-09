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
                this.status_check(e, $, res, ajax_action);
            }
        })
    }
    status_check(e, $, res, ajax_action) {
        console.log(res)
        if (ajax_action == 'ud_department') {
            this.common_msg(e, res, $);
        }

        if (ajax_action == 'ud_teacher') {
            if (res == 'updated' || res == 'allowed') {
                let text = (res == 'allowed' ? 'User allowed as a teacher' : 'Updated Successfully!');
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
                let text = 'Something went wrong in deleting teacher!';
                this.output('error', text, $);
            }
            if (res == 'failed') {
                let text = 'Something went wrong!';
                this.output('error', text, $);
            }
            if (res == 'allowed' || res == 'restricted') {
                setTimeout(() => {
                    location.reload();
                }, 3500);
            }
        }

        if (ajax_action == 'ud_question') {
            if (res == 'empty_dept') {
                let text = 'Please select a department';
                this.output('error', text, $);
            }
            if (res == 'empty_question_field') {
                let text = 'One or more question field is empty';
                this.output('warning', text, $);
            }
            if (res == 'published') {
                let text = 'Exam published sucessfully';
                this.output('success', text, $);
                $(e.currentTarget).parent().parent().find('.oe-terminate').addClass('ow-yellow-active');
                $(e.currentTarget).parent().parent().find('.exam_status').html('Running');
                $(e.currentTarget).parent().parent().find('.question_dept_id').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=exam_folder_name]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=quantity]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=exam_time]').attr('disabled', true);
                $(e.currentTarget).parent().parent().find('input[name=pass_percentage]').attr('disabled', true);
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