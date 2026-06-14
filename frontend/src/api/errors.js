export function getApiErrorMessage(error, fallback) {
  const status = error.response?.status
  const errors = error.response?.data?.errors
  const message = error.response?.data?.message

  if (errors) {
    return Object.values(errors).flat()[0] || fallback
  }

  if (status >= 500) {
    return fallback
  }

  if (message && !message.startsWith('SQLSTATE')) {
    return message
  }

  if (error.request) {
    return 'Could not reach the API. Check that the backend server is running.'
  }

  if (error.message) {
    return error.message
  }

  return fallback
}
