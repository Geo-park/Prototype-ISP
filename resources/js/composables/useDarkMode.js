import { ref, onMounted, watch } from 'vue'

const isDark = ref(false)

export function useDarkMode() {
    onMounted(() => {
        const saved = localStorage.getItem('darkMode')
        isDark.value = saved === 'true'
        applyDark(isDark.value)
    })

    const toggle = () => {
        isDark.value = !isDark.value
        localStorage.setItem('darkMode', isDark.value)
        applyDark(isDark.value)
    }

    const applyDark = (dark) => {
        if (dark) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    }

    return { isDark, toggle }
}
