/** Test data for ODD Care Co E2E tests */

export const PRODUCTS = {
  clearFirst: {
    name: 'Clear First',
    slug: 'clear-first',
    sku: 'ODD-01',
    code: 'ODD 01',
    price: '₹499',
    priceNum: 499,
    type: 'Facewash',
    editorialUrl: '/clear-first/',
    shopUrl: '/shop/?add-to-cart=112',
    productId: 112,
  },
  foamRinse: {
    name: 'Foam Rinse',
    slug: 'foam-rinse',
    sku: 'ODD-02',
    code: 'ODD 02',
    price: '₹449',
    priceNum: 449,
    type: 'Body Wash',
    editorialUrl: '/foam-rinse/',
    shopUrl: '/shop/?add-to-cart=113',
    productId: 113,
  },
  dawnShield: {
    name: 'Dawn Shield',
    slug: 'dawn-shield',
    sku: 'ODD-03',
    code: 'ODD 03',
    price: '₹399',
    priceNum: 399,
    type: 'AM Cream',
    editorialUrl: '/dawn-shield/',
    shopUrl: '/shop/?add-to-cart=114',
    productId: 114,
  },
  deepDusk: {
    name: 'Deep Dusk',
    slug: 'deep-dusk',
    sku: 'ODD-04',
    code: 'ODD 04',
    price: '₹549',
    priceNum: 549,
    type: 'PM Cream',
    editorialUrl: '/deep-dusk/',
    shopUrl: '/shop/?add-to-cart=115',
    productId: 115,
  },
  bundle: {
    name: 'The Group Project',
    slug: 'the-whole-routine',
    sku: 'ODD-BUNDLE',
    code: 'ALL 4',
    price: '₹1,499',
    priceNum: 1499,
    type: 'Complete Bundle',
    editorialUrl: '/the-whole-routine/',
    shopUrl: '/shop/?add-to-cart=116',
    productId: 116,
  },
} as const

export const TEST_USER = {
  username: 'testcustomer',
  email: 'testcustomer@oddcareco.test',
  password: 'TestPass123!',
  firstName: 'Test',
  lastName: 'Customer',
} as const

export const ADMIN_USER = {
  username: 'admin',
  password: 'admin', // Local by Flywheel default
} as const

export const PAGES = {
  home: '/',
  shop: '/shop/',
  cart: '/cart/',
  checkout: '/checkout/',
  myAccount: '/my-account/',
  manifesto: '/why-4/',
  ourMission: '/our-mission/',
} as const
