import type {App} from "vue"

export default {
    install(app: App, options) {
        app.directive('click-outside', {
            mounted(el, binding, vnode) {
                el.clickOutsideEvent = function(event) {
                    if (el === event.target || el.contains(event.target)) {
                        return;
                    }

                    binding.value(event, el);
                };

                document
                    .body
                    .addEventListener(
                        'click',
                        el.clickOutsideEvent,
                    );
            },
            unmounted(el) {
                document
                    .body
                    .removeEventListener(
                        'click',
                        el.clickOutsideEvent,
                    );
            },
        });
    }
}
