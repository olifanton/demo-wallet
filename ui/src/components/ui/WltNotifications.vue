<template>

</template>

<script lang="ts">
import {defineComponent} from "vue";
import {useNotificationStore} from "@/stores/notification-store";

export default defineComponent({
    name: "wlt-notifications",
    created() {
        const store = useNotificationStore();

        store.$subscribe((mutation: WltStoreMutation & any, state) => {

            if (mutation.storeId === "notification" && mutation.events.type === 'set') {
                const ev = mutation.events;
                const hop = Object.prototype.hasOwnProperty;

                if (hop.call(ev.newValue, ['title']) && hop.call(ev.newValue, ['message'])) {
                    let color: string | null = null;
                    let icon: string | null = null;

                    switch (ev.key) {
                        case "error":
                            color = "danger";
                            icon = "warning";
                            break;

                        case "success":
                            color = "success";
                            icon = "check_circle";
                            break;

                        case "text":
                            color = "dark";
                            icon = "info";
                            break;
                    }

                    if (color && icon) {
                        this.$vs.notify({
                            time: 3000,
                            title: ev.newValue.title,
                            text: ev.newValue.message,
                            color: color,
                            position: "top-center",
                            icon: icon,
                        });
                    }
                }
            }
        });
    },
});
</script>
