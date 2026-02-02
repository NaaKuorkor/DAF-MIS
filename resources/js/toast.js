import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

const baseConfig = {
    duration: 3500,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    close: true,
};

const toast = {
    success(message) {
        Toastify({
            ...baseConfig,
            text: message,
            backgroundColor: "#ECFDF5",
            style: { color: "#065F46" },
        }).showToast();
    },

    error(message) {
        Toastify({
            ...baseConfig,
            text: message,
            backgroundColor: "#FEF2F2",
            style: { color: "#991B1B" },
        }).showToast();
    },

    info(message) {
        Toastify({
            ...baseConfig,
            text: message,
            backgroundColor: "#F5F3FF",
            style: { color: "#4C1D95" },
        }).showToast();
    },

    warning(message) {
        Toastify({
            ...baseConfig,
            text: message,
            backgroundColor: "#FFFBEB",
            style: { color: "#92400E" },
        }).showToast();
    },
};

export default toast;
