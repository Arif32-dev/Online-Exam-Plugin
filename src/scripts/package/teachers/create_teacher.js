jQuery(document).ready(function($) {
    class Teacher {
        constructor() {
            this.form = $('#ow_teacher_form');
            this.random_pass = $('#ow_teacher_form span');
            this.events();
        }
        events() {
            this.form.on('submit', this.handle_submit);
            this.random_pass.on('click', this.genarate_pass);
        }
        handle_submit(e) {
            e.preventDefault();
            const form_data = $(this).serialize();
            $.ajax({
                url: file_url.create_teacher_url,
                data: form_data,
                type: 'post',
                success: res => {
                    // console.log(res)
                    if (res == 'invalid_dept') {
                        $('.oe-notification').html(`
                                <div class="notice notice-error is-dismissible">
                                    <p> Please Choose a department! If department dont exists please create one </p> 
                                </div>
                            `).hide().slideDown();
                    }
                    if (res == 'invalid_number') {
                        $('.oe-notification').html(`
                                <div class="notice notice-error is-dismissible">
                                    <p> Your number is incorrect . Please use a valid number </p> 
                                </div>
                            `).hide().slideDown();
                    }
                    if (res == 'user_not_created') {
                        $('.oe-notification').html(`
                                <div class="notice notice-error is-dismissible">
                                    <p> Something went wrong in teacher assignment </p> 
                                </div>
                            `).hide().slideDown();
                    }
                    if (res == 'user_role_invalid') {
                        $('.oe-notification').html(`
                                <div class="notice notice-error is-dismissible">
                                    <p> User Role dont exists! Please re-activate the plugin to fix this issue or contact Developer to fix this issue</p> 
                                </div>
                            `).hide().slideDown();
                    }
                    if (res == 'success') {
                        location.reload();
                    }
                    setTimeout(() => {
                        $('.oe-notification').slideUp();
                    }, 4000);
                }
            })
        }
        genarate_pass(e) {
            let randomstring = Math.random().toString(36).slice(-10);
            $(e.currentTarget).parent().find('input').val(randomstring);
        }
    }
    new Teacher();
})