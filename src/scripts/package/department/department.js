jQuery(document).ready(function($) {
    class Department {
        constructor() {
            this.form = $('#department_form');
            this.events();
        }
        events() {
            this.form.on('submit', this.handle_submit);
        }
        handle_submit(e) {
            e.preventDefault();
            const form_data = $(this).serialize();
            $.ajax({
                url: file_url.department_url,
                data: form_data,
                type: 'post',
                success: res => {
                    console.log(res)
                    if (res == 'empty_name') {
                        $('.oe-notification').html(`
                            <div class="notice notice-error is-dismissible">
                                <p> Department name is required! </p> 
                            </div>
                        `).hide().slideDown();
                    }
                    if (res == 'department_exists') {
                        $('.oe-notification').html(`
                            <div class="notice notice-error is-dismissible">
                                <p> Department name already exists. Please enter another department! </p> 
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
    new Department();
})