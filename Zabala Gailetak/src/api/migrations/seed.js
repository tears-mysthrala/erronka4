const { connectDatabase, disconnectDatabase } = require('../config/database');
const User = require('../models/User');
const Product = require('../models/Product');
const Order = require('../models/Order');
const AuditLog = require('../models/AuditLog');

/**
 * Database Seed Script
 * 
 * Populates the database with initial data for development and testing
 */

const seedUsers = async () => {
  console.log('[Seed] Creating users...');
  
  const users = [
    {
      username: 'admin',
      email: 'admin@zabala-gailetak.com',
      password: 'Admin123!@#',
      role: 'admin',
      mfaEnabled: false
    },
    {
      username: 'user1',
      email: 'user1@example.com',
      password: 'User123!@#',
      role: 'user',
      mfaEnabled: false
    },
    {
      username: 'user2',
      email: 'user2@example.com',
      password: 'User123!@#',
      role: 'user',
      mfaEnabled: true
    }
  ];
  
  const createdUsers = [];
  for (const userData of users) {
    try {
      const existingUser = await User.findOne({ username: userData.username });
      if (!existingUser) {
        const user = await User.create(userData);
        createdUsers.push(user);
        console.log(`[Seed] ✓ Created user: ${user.username} (${user.role})`);
      } else {
        console.log(`[Seed] ⊘ User already exists: ${userData.username}`);
        createdUsers.push(existingUser);
      }
    } catch (error) {
      console.error(`[Seed] ✗ Failed to create user ${userData.username}:`, error.message);
    }
  }
  
  return createdUsers;
};

const seedProducts = async () => {
  console.log('[Seed] Creating products...');
  
  const products = [
    {
      name: 'Gaileta Tradizionalak',
      description: 'Gure gaileta klasikoak, errezeta tradizionalekin eginak. Familiako sekretua 1950az geroztik.',
      price: 2.50,
      stock: 150,
      category: 'tradizionalak',
      ingredients: ['Irina', 'Azukrea', 'Gurina', 'Arrautzak', 'Legamia'],
      allergens: ['gluten', 'lactose', 'eggs'],
      nutritionalInfo: {
        calories: 450,
        protein: 6,
        carbohydrates: 65,
        fat: 18,
        sugar: 25,
        fiber: 2
      },
      weight: 200
    },
    {
      name: 'Txokolatezko Gailetak',
      description: 'Txokolate beltzarekin eginak, intentsitate altuko zapore batekin. Txokolate zaleentzat.',
      price: 3.00,
      stock: 120,
      category: 'txokolatea',
      ingredients: ['Irina', 'Azukrea', 'Gurina', 'Arrautzak', 'Txokolatea (70%)', 'Kakao hautsa'],
      allergens: ['gluten', 'lactose', 'eggs'],
      nutritionalInfo: {
        calories: 480,
        protein: 7,
        carbohydrates: 58,
        fat: 22,
        sugar: 28,
        fiber: 4
      },
      weight: 200
    },
    {
      name: 'Zereal Gailetak',
      description: 'Zereal osoarekin eta haziekin, osasuntsu eta aberatsak zuntzean. Gosari perfektua.',
      price: 2.80,
      stock: 100,
      category: 'zereal',
      ingredients: ['Gari osoa', 'Oloa', 'Eztia', 'Eguzkiaren haziak', 'Lino haziak', 'Gurina'],
      allergens: ['gluten'],
      nutritionalInfo: {
        calories: 380,
        protein: 9,
        carbohydrates: 55,
        fat: 12,
        sugar: 15,
        fiber: 8
      },
      weight: 220
    },
    {
      name: 'Gaileta Glutengabeak',
      description: 'Glutenik gabeko gaileta gozoak, zeliakentzat egokiak. Zapore guztiak, glutenik gabe.',
      price: 3.50,
      stock: 80,
      category: 'glutengabe',
      ingredients: ['Irina glutengabea', 'Azukrea', 'Gurina', 'Arrautzak', 'Legamia'],
      allergens: ['lactose', 'eggs'],
      nutritionalInfo: {
        calories: 420,
        protein: 5,
        carbohydrates: 62,
        fat: 16,
        sugar: 22,
        fiber: 3
      },
      weight: 180
    },
    {
      name: 'Gaileta Organikoak',
      description: 'Osagai guztiak organikoak, ziurtagiria dute. Zure osasunaren eta ingurumenaren alde.',
      price: 4.00,
      stock: 60,
      category: 'organikoak',
      ingredients: ['Gari osoa organikoa', 'Azukre organikoa', 'Olio organikoa', 'Arrautza organikoak'],
      allergens: ['gluten', 'eggs'],
      nutritionalInfo: {
        calories: 400,
        protein: 8,
        carbohydrates: 58,
        fat: 14,
        sugar: 18,
        fiber: 6
      },
      weight: 200
    }
  ];
  
  const createdProducts = [];
  for (const productData of products) {
    try {
      const existingProduct = await Product.findOne({ name: productData.name });
      if (!existingProduct) {
        const product = await Product.create(productData);
        createdProducts.push(product);
        console.log(`[Seed] ✓ Created product: ${product.name} (SKU: ${product.sku})`);
      } else {
        console.log(`[Seed] ⊘ Product already exists: ${productData.name}`);
        createdProducts.push(existingProduct);
      }
    } catch (error) {
      console.error(`[Seed] ✗ Failed to create product ${productData.name}:`, error.message);
    }
  }
  
  return createdProducts;
};

const seedOrders = async (users, products) => {
  console.log('[Seed] Creating sample orders...');
  
  if (users.length === 0 || products.length === 0) {
    console.log('[Seed] ⊘ Skipping orders - no users or products available');
    return [];
  }
  
  const orders = [
    {
      user: users[1]._id,
      customerEmail: users[1].email,
      customerName: 'User One',
      items: [
        {
          product: products[0]._id,
          productName: products[0].name,
          quantity: 2,
          price: products[0].price,
          subtotal: products[0].price * 2
        },
        {
          product: products[1]._id,
          productName: products[1].name,
          quantity: 1,
          price: products[1].price,
          subtotal: products[1].price * 1
        }
      ],
      subtotal: (products[0].price * 2) + products[1].price,
      taxRate: 0.21,
      taxAmount: 0,
      shippingCost: 5.00,
      totalAmount: 0,
      status: 'delivered',
      paymentStatus: 'paid',
      paymentMethod: 'credit_card',
      paymentTransactionId: 'TXN-' + Date.now(),
      shippingAddress: {
        fullName: 'User One',
        addressLine1: 'Kale Nagusia 123',
        city: 'Donostia',
        state: 'Gipuzkoa',
        postalCode: '20001',
        country: 'Spain',
        phone: '+34 943 123 456'
      },
      actualDeliveryDate: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000) // 2 days ago
    },
    {
      customerEmail: 'guest@example.com',
      customerName: 'Guest User',
      items: [
        {
          product: products[2]._id,
          productName: products[2].name,
          quantity: 3,
          price: products[2].price,
          subtotal: products[2].price * 3
        }
      ],
      subtotal: products[2].price * 3,
      taxRate: 0.21,
      taxAmount: 0,
      shippingCost: 5.00,
      totalAmount: 0,
      status: 'processing',
      paymentStatus: 'paid',
      paymentMethod: 'paypal',
      paymentTransactionId: 'PP-' + Date.now(),
      shippingAddress: {
        fullName: 'Guest User',
        addressLine1: 'Erribera Kalea 45',
        city: 'Bilbo',
        state: 'Bizkaia',
        postalCode: '48001',
        country: 'Spain',
        phone: '+34 944 234 567'
      }
    }
  ];
  
  const createdOrders = [];
  for (const orderData of orders) {
    try {
      const order = await Order.create(orderData);
      createdOrders.push(order);
      console.log(`[Seed] ✓ Created order: ${order.orderNumber} (${order.status})`);
    } catch (error) {
      console.error(`[Seed] ✗ Failed to create order:`, error.message);
    }
  }
  
  return createdOrders;
};

const seedAuditLogs = async (users) => {
  console.log('[Seed] Creating sample audit logs...');
  
  const logs = [
    {
      action: 'auth.login.success',
      severity: 'low',
      user: users[0]?._id,
      username: users[0]?.username,
      ipAddress: '192.168.1.100',
      userAgent: 'Mozilla/5.0',
      endpoint: '/api/auth/login',
      method: 'POST',
      statusCode: 200,
      message: 'User logged in successfully',
      success: true,
      timestamp: new Date(Date.now() - 1 * 60 * 60 * 1000) // 1 hour ago
    },
    {
      action: 'auth.login.failed',
      severity: 'medium',
      username: 'unknown',
      ipAddress: '203.0.113.42',
      userAgent: 'curl/7.68.0',
      endpoint: '/api/auth/login',
      method: 'POST',
      statusCode: 401,
      message: 'Invalid credentials',
      success: false,
      errorMessage: 'Username or password incorrect',
      timestamp: new Date(Date.now() - 30 * 60 * 1000) // 30 minutes ago
    },
    {
      action: 'product.create',
      severity: 'low',
      user: users[0]?._id,
      username: users[0]?.username,
      ipAddress: '192.168.1.100',
      resource: 'Product',
      endpoint: '/api/products',
      method: 'POST',
      statusCode: 201,
      message: 'Product created',
      success: true,
      timestamp: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000) // 2 days ago
    }
  ];
  
  for (const logData of logs) {
    try {
      await AuditLog.log(logData);
      console.log(`[Seed] ✓ Created audit log: ${logData.action}`);
    } catch (error) {
      console.error(`[Seed] ✗ Failed to create audit log:`, error.message);
    }
  }
};

/**
 * Main seed function
 */
const seedDatabase = async () => {
  try {
    console.log('[Seed] Starting database seeding...');
    console.log('[Seed] ===================================');
    
    // Connect to database
    await connectDatabase();
    
    // Seed data in order
    const users = await seedUsers();
    const products = await seedProducts();
    const orders = await seedOrders(users, products);
    await seedAuditLogs(users);
    
    console.log('[Seed] ===================================');
    console.log('[Seed] ✓ Database seeding completed successfully');
    console.log(`[Seed] Summary:`);
    console.log(`[Seed]   - Users: ${users.length}`);
    console.log(`[Seed]   - Products: ${products.length}`);
    console.log(`[Seed]   - Orders: ${orders.length}`);
    
    // Disconnect
    await disconnectDatabase();
    
  } catch (error) {
    console.error('[Seed] ✗ Error seeding database:', error);
    process.exit(1);
  }
};

// Run seed if this file is executed directly
if (require.main === module) {
  seedDatabase();
}

module.exports = { seedDatabase };
