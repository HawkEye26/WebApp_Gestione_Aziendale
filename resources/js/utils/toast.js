// Utility per gestire i toast in Vue
export function showToast(type, title, message = '') {
  const event = new CustomEvent('show-toast', {
    detail: {
      type, // 'success', 'error', 'warning', 'info'
      title,
      message
    }
  });
  
  window.dispatchEvent(event);
}

// Shorthand methods
export const toast = {
  success: (title, message) => showToast('success', title, message),
  error: (title, message) => showToast('error', title, message),
  warning: (title, message) => showToast('warning', title, message),
  info: (title, message) => showToast('info', title, message)
};

// Esempio di utilizzo:
// import { toast } from '@/utils/toast';
// toast.success('Successo!', 'Operazione completata con successo');