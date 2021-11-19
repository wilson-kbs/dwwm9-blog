export * from './diacritics'
export * from './slug'

const API_BASE_PATH = "/api";

type ApiEndpoints = {
  [key: string]: string;
  CATEGORIES: string;
  POSTS: string;
  USER: string;
  LOGIN: string;
  SIGNUP: string;
};

export const API_ENDPOINTS: ApiEndpoints = {
  POSTS: "/posts",
  CATEGORIES: "/categories",
  USER: "/me",
  LOGIN: "/login",
  SIGNUP: "/signup",
};

for (const prop in API_ENDPOINTS) {
  const newValue = API_BASE_PATH + API_ENDPOINTS[prop];
  API_ENDPOINTS[prop] = newValue;
}

Object.defineProperties(API_ENDPOINTS, {
  LOGIN: {
    writable: false,
  },
  SIGNUP: {
    writable: false,
  },
  USER: {
    writable: false,
  },
  CATEGORIES: {
    writable: false,
  },
  POSTS: {
    writable: false,
  },
});
