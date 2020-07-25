jQuery(document).ready(function($) {
    class OE_create_routine {
        constructor() {
            this.form = $('#exam-routine_form');
            this.events();
        }
        events() {
            this.form.on('submit', this.handle_submit);
        }
        handle_submit(e) {
            e.preventDefault();
            const form_data = $(this).serialize();
            $.ajax({
                url: file_url.exam_routine_url,
                data: form_data,
                type: 'post',
                success: res => {
                    console.log(res)
                    if (res == 'empty_dept') {
                        $('.oe-notification').html(`
                            <div class="notice notice-error is-dismissible">
                                <p> Please select a department </p> 
                            </div>
                        `).hide().slideDown();
                    }
                    if (res == 'empty_field') {
                        $('.oe-notification').html(`
                            <div class="notice notice-error is-dismissible">
                                <p> One or more field is empty </p> 
                            </div>
                        `).hide().slideDown();
                    }
                    if (res == 'failed') {
                        $('.oe-notification').html(`
                            <div class="notice notice-error is-dismissible">
                                <p> Something went wrong! </p> 
                            </div>
                        `).hide().slideDown();
                    }
                    if (res == 'success') {
                        location.reload();
                    }
                    setTimeout(() => {
                        $('.oe-notification').slideUp();
                    }, 3000);
                }
            })
        }
    }
    new OE_create_routine();
})