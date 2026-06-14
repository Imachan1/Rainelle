export function getApiErrorMessage(error, fallback) {
  if (error.response?.data?.message) {
    return error.response.data.message
  }

  if (error.request) {
    return 'Could not reach the API. Check that the backend server is running.'
  }

  if (error.message) {
    return error.message
  }

  return fallback
}
