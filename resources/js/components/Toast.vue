<template>
  <div
    v-if="show"
    class="fixed top-4 right-4 z-50 max-w-sm w-full"
    :class="toastClass"
  >
    <div class="rounded-lg shadow-lg p-4">
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg v-if="type === 'error'" class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <svg v-if="type === 'success'" class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-if="type === 'warning'" class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3 w-0 flex-1">
          <p class="text-sm font-medium" :class="textClass">
            {{ title }}
          </p>
          <p v-if="message" class="mt-1 text-sm" :class="messageClass">
            {{ message }}
          </p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
          <button
            @click="close"
            class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
          >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'

export default {
  name: 'Toast',
  props: {
    type: {
      type: String,
      default: 'error',
      validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
      type: String,
      required: true
    },
    message: {
      type: String,
      default: ''
    },
    duration: {
      type: Number,
      default: 5000
    },
    autoClose: {
      type: Boolean,
      default: true
    }
  },
  emits: ['close'],
  setup(props, { emit }) {
    const show = ref(false)

    const toastClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'bg-green-50 border border-green-200'
        case 'error':
          return 'bg-red-50 border border-red-200'
        case 'warning':
          return 'bg-yellow-50 border border-yellow-200'
        case 'info':
          return 'bg-blue-50 border border-blue-200'
        default:
          return 'bg-red-50 border border-red-200'
      }
    })

    const textClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'text-green-800'
        case 'error':
          return 'text-red-800'
        case 'warning':
          return 'text-yellow-800'
        case 'info':
          return 'text-blue-800'
        default:
          return 'text-red-800'
      }
    })

    const messageClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'text-green-700'
        case 'error':
          return 'text-red-700'
        case 'warning':
          return 'text-yellow-700'
        case 'info':
          return 'text-blue-700'
        default:
          return 'text-red-700'
      }
    })

    const close = () => {
      show.value = false
      emit('close')
    }

    onMounted(() => {
      show.value = true
      
      if (props.autoClose && props.duration > 0) {
        setTimeout(() => {
          close()
        }, props.duration)
      }
    })

    return {
      show,
      toastClass,
      textClass,
      messageClass,
      close
    }
  }
}
</script>