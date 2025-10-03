import { ref, nextTick } from 'vue'

const toasts = ref([])

export function useToast() {
  const addToast = (toast) => {
    const id = Date.now() + Math.random()
    const newToast = {
      id,
      ...toast
    }
    
    toasts.value.push(newToast)
    
    const duration = toast.duration || 5000
    if (toast.autoClose !== false && duration > 0) {
      setTimeout(() => {
        removeToast(id)
      }, duration)
    }
    
    return id
  }

  const removeToast = (id) => {
    const index = toasts.value.findIndex(toast => toast.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  const clearToasts = () => {
    toasts.value = []
  }

  const showSuccess = (title, message = '', options = {}) => {
    return addToast({
      type: 'success',
      title,
      message,
      ...options
    })
  }

  const showError = (title, message = '', options = {}) => {
    return addToast({
      type: 'error',
      title,
      message,
      ...options
    })
  }

  const showWarning = (title, message = '', options = {}) => {
    return addToast({
      type: 'warning',
      title,
      message,
      ...options
    })
  }

  const showInfo = (title, message = '', options = {}) => {
    return addToast({
      type: 'info',
      title,
      message,
      ...options
    })
  }

  return {
    toasts,
    addToast,
    removeToast,
    clearToasts,
    showSuccess,
    showError,
    showWarning,
    showInfo
  }
}