const BASE_URL = import.meta.env.VITE_API_URL || '/api/v1';

class ApiClient {
  private getHeaders(): HeadersInit {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
    };
    const token = localStorage.getItem('token');
    if (token) {
      headers['Authorization'] = 'Bearer ' + token;
    }
    return headers;
  }

  async get<T>(url: string): Promise<T> {
    const res = await fetch(BASE_URL + url, {
      headers: this.getHeaders(),
    });
    return this.handleResponse<T>(res);
  }

  async post<T>(url: string, data?: unknown): Promise<T> {
    const res = await fetch(BASE_URL + url, {
      method: 'POST',
      headers: this.getHeaders(),
      body: data ? JSON.stringify(data) : undefined,
    });
    return this.handleResponse<T>(res);
  }

  async put<T>(url: string, data?: unknown): Promise<T> {
    const res = await fetch(BASE_URL + url, {
      method: 'PUT',
      headers: this.getHeaders(),
      body: data ? JSON.stringify(data) : undefined,
    });
    return this.handleResponse<T>(res);
  }

  async delete<T>(url: string): Promise<T> {
    const res = await fetch(BASE_URL + url, {
      method: 'DELETE',
      headers: this.getHeaders(),
    });
    return this.handleResponse<T>(res);
  }

  private async handleResponse<T>(res: Response): Promise<T> {
    if (res.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/masuk';
      throw new Error('Unauthorized');
    }

    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Request failed');
    }
    return data;
  }
}

export const apiClient = new ApiClient();
