import "../css/app.css";
import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import Swal from "sweetalert2";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Configuração Global do SweetAlert "Apple Style"
const Toast = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        popup: "rounded-2xl shadow-xl border border-gray-100 bg-white/90 backdrop-blur-md",
        title: "text-sm font-semibold text-gray-800",
    },
});

const AppleModal = Swal.mixin({
    customClass: {
        popup: "rounded-3xl shadow-2xl",
        confirmButton:
            "bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-6 py-2 font-medium",
        cancelButton:
            "bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl px-6 py-2 font-medium",
        title: "text-xl font-semibold",
    },
    buttonsStyling: false,
});

window.Swal = AppleModal;
window.Toast = Toast;

import { vMaska } from "maska/vue";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .directive("maska", vMaska)
            .mount(el);
    },
    progress: {
        color: '#2563EB', // blue-600
        showSpinner: true,
    },
});

// Listener global para flash messages
router.on("finish", () => {
    const flash = router.page.props.flash;
    if (flash?.success) {
        AppleModal.fire({
            icon: "success",
            title: "Sucesso",
            text: flash.success,
            timer: 3000,
            showConfirmButton: false,
        });
    }
    if (flash?.error) {
        AppleModal.fire({
            icon: "error",
            title: "Erro",
            text: flash.error,
        });
    }
    if (flash?.warning) {
        AppleModal.fire({
            icon: "warning",
            title: "Atenção",
            text: flash.warning,
        });
    }
});
