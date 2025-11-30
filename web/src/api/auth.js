import http from './http'

export async function login(email, password) {
  // Get CSRF cookie required by Sanctum
  await http.get('/sanctum/csrf-cookie')

  // Login
  await http.post('/login', {
    email,
    password,
  })
  // If successful, browser now has a session cookie
}

export async function fetchCurrentUser() {
  const { data } = await http.get('/api/user')
  return data
}

export async function logout() {
  await http.post('/logout')
}
