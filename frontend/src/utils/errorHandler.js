export function getErrorMessage(error) {
  if (!error.response) {
    return {
      title: 'Connection Error',
      message: 'Unable to connect to the server. Please check your internet connection and try again.'
    }
  }
  
  
  const status = error.response.status
  
  if (status === 404) {
    return {
      title: 'Not Found',
      message: 'The requested resource was not found.'
    }
  }
  
  if (status === 500) {
    return {
      title: 'Server Error',
      message: 'The server encountered an error. Please try again later.'
    }
  }
  
  if (status === 403) {
    return {
      title: 'Access Denied',
      message: 'You do not have permission to perform this action.'
    }
  }
  
  
  return {
    title: 'Error',
    message: error.response.data.message || 'Something went wrong. Please try again.'
  }
}
