jQuery(document).ready(function($) {
    class OE_create_qus {
        constructor() {
            this.form = $('.oe-qustion_submit');
            this.events();
        }
        events() {
            this.form.on('submit', this.handle_submit);
        }
        handle_submit(e) {
            e.preventDefault();
            const form_data = $(this).serialize();
            $.ajax({
                url: file_url.single_qus_url,
                data: form_data,
                type: 'post',
                success: res => {
                    console.log(res)
                    if (res == 'success') {
                        $('.oe-notification').html(`
                                <div class="notice notice-success is-dismissible">
                                    <p> Successfully created </p> 
                                </div>
                        `).hide().slideDown();
                    }
                    if (res == 'empty_field') {
                        $('.oe-notification').html(`
                                <div class="notice notice-warning is-dismissible">
                                    <p> Field is empty </p> 
                                </div>
                        `).hide().slideDown();
                    }
                    if (res == 'empty_correct_ans') {
                        $('.oe-notification').html(`
                                <div class="notice notice-warning is-dismissible">
                                    <p> Correct answer is required </p> 
                                </div>
                        `).hide().slideDown();
                    }
                    if (res == 'updated') {
                        $('.oe-notification').html(`
                                <div class="notice notice-success is-dismissible">
                                    <p> Successfully updated </p> 
                                </div>
                        `).hide().slideDown();
                    }
                    if (res == 'not_updated') {
                        $('.oe-notification').html(`
                                <div class="notice notice-warning is-dismissible">
                                    <p> Nothing changed to update </p> 
                                </div>
                        `).hide().slideDown();
                    }
                    if (res == 'failed') {
                        $('.oe-notification').html(`
                                <div class="notice notice-error is-dismissible">
                                    <p> Something went wrong </p> 
                                </div>
                        `).hide().slideDown();
                    }
                    setTimeout(() => {
                        $('.oe-notification').slideUp();
                    }, 2500);
                }
            })
        }

    }
    new OE_create_qus();
})