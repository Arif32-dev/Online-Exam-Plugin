jQuery(document).ready(function($) {
    class OE_Create_qus {
        constructor() {
            this.form = $('#question_create_form');
            this.events();
        }
        events() {
            this.form.on('submit', this.handle_submit);
        }
        handle_submit(e) {
            e.preventDefault();
            const form_data = $(this).serialize();
            $.ajax({
                url: file_url.create_question_url,
                data: form_data,
                type: 'post',
                success: res => {
                    console.log(res)
                    if (res == 'empty_dept') {
                        $('.oe-notification').html(`
                            <div class="notice notice-error is-dismissible">
                                <p> Department name is required! </p> 
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
    new OE_Create_qus();
})