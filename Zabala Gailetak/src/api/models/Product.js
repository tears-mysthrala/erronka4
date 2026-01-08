const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
  name: {
    type: String,
    required: [true, 'Product name is required'],
    trim: true,
    minlength: [2, 'Product name must be at least 2 characters'],
    maxlength: [100, 'Product name cannot exceed 100 characters']
  },
  description: {
    type: String,
    trim: true,
    maxlength: [1000, 'Description cannot exceed 1000 characters']
  },
  price: {
    type: Number,
    required: [true, 'Price is required'],
    min: [0, 'Price cannot be negative'],
    set: val => Math.round(val * 100) / 100 // Round to 2 decimal places
  },
  stock: {
    type: Number,
    required: [true, 'Stock quantity is required'],
    min: [0, 'Stock cannot be negative'],
    default: 0
  },
  category: {
    type: String,
    required: [true, 'Category is required'],
    enum: ['tradizionalak', 'txokolatea', 'zereal', 'glutengabe', 'organikoak'],
    default: 'tradizionalak'
  },
  sku: {
    type: String,
    unique: true,
    sparse: true,
    trim: true,
    uppercase: true
  },
  imageUrl: {
    type: String,
    trim: true
  },
  isActive: {
    type: Boolean,
    default: true
  },
  ingredients: [{
    type: String,
    trim: true
  }],
  allergens: [{
    type: String,
    trim: true,
    enum: ['gluten', 'lactose', 'nuts', 'eggs', 'soy']
  }],
  nutritionalInfo: {
    calories: { type: Number, min: 0 },
    protein: { type: Number, min: 0 },
    carbohydrates: { type: Number, min: 0 },
    fat: { type: Number, min: 0 },
    sugar: { type: Number, min: 0 },
    fiber: { type: Number, min: 0 }
  },
  weight: {
    type: Number, // in grams
    min: [0, 'Weight cannot be negative']
  },
  dimensions: {
    length: { type: Number, min: 0 },
    width: { type: Number, min: 0 },
    height: { type: Number, min: 0 }
  },
  manufacturer: {
    type: String,
    trim: true,
    default: 'Zabala Gailetak S.L.'
  },
  createdAt: {
    type: Date,
    default: Date.now
  },
  updatedAt: {
    type: Date,
    default: Date.now
  }
}, {
  timestamps: true
});

// Indexes for efficient queries
productSchema.index({ name: 1 });
productSchema.index({ category: 1 });
productSchema.index({ sku: 1 });
productSchema.index({ isActive: 1, stock: 1 });
productSchema.index({ price: 1 });

// Virtual for checking if product is in stock
productSchema.virtual('inStock').get(function() {
  return this.stock > 0;
});

// Virtual for low stock warning
productSchema.virtual('lowStock').get(function() {
  return this.stock > 0 && this.stock <= 10;
});

// Method to check stock availability
productSchema.methods.checkAvailability = function(requestedQuantity) {
  if (!this.isActive) {
    return { available: false, reason: 'Product is not active' };
  }
  if (this.stock < requestedQuantity) {
    return { available: false, reason: 'Insufficient stock', availableStock: this.stock };
  }
  return { available: true, availableStock: this.stock };
};

// Method to reduce stock
productSchema.methods.reduceStock = async function(quantity) {
  if (this.stock < quantity) {
    throw new Error('Insufficient stock');
  }
  this.stock -= quantity;
  return await this.save();
};

// Method to increase stock
productSchema.methods.increaseStock = async function(quantity) {
  this.stock += quantity;
  return await this.save();
};

// Pre-save middleware to generate SKU if not provided
productSchema.pre('save', function(next) {
  if (!this.sku && this.isNew) {
    // Generate SKU: CAT-FIRST3CHARS-TIMESTAMP
    const categoryCode = this.category.substring(0, 3).toUpperCase();
    const nameCode = this.name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '');
    const timestamp = Date.now().toString().slice(-6);
    this.sku = `${categoryCode}-${nameCode}-${timestamp}`;
  }
  next();
});

// Ensure virtuals are included when converting to JSON
productSchema.set('toJSON', { 
  virtuals: true,
  transform: function(doc, ret) {
    delete ret.__v;
    return ret;
  }
});

const Product = mongoose.model('Product', productSchema);

module.exports = Product;
