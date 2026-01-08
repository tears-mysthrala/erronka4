const mongoose = require('mongoose');

const orderItemSchema = new mongoose.Schema({
  product: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Product',
    required: true
  },
  productName: {
    type: String,
    required: true
  },
  quantity: {
    type: Number,
    required: true,
    min: [1, 'Quantity must be at least 1']
  },
  price: {
    type: Number,
    required: true,
    min: [0, 'Price cannot be negative']
  },
  subtotal: {
    type: Number,
    required: true,
    min: [0, 'Subtotal cannot be negative']
  }
}, { _id: false });

const shippingAddressSchema = new mongoose.Schema({
  fullName: {
    type: String,
    required: true,
    trim: true
  },
  addressLine1: {
    type: String,
    required: true,
    trim: true
  },
  addressLine2: {
    type: String,
    trim: true
  },
  city: {
    type: String,
    required: true,
    trim: true
  },
  state: {
    type: String,
    required: true,
    trim: true
  },
  postalCode: {
    type: String,
    required: true,
    trim: true
  },
  country: {
    type: String,
    required: true,
    trim: true,
    default: 'Spain'
  },
  phone: {
    type: String,
    required: true,
    trim: true
  }
}, { _id: false });

const orderSchema = new mongoose.Schema({
  orderNumber: {
    type: String,
    unique: true,
    required: true,
    index: true
  },
  user: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: false // Allow guest orders
  },
  customerEmail: {
    type: String,
    required: [true, 'Customer email is required'],
    trim: true,
    lowercase: true
  },
  customerName: {
    type: String,
    required: [true, 'Customer name is required'],
    trim: true
  },
  items: [orderItemSchema],
  subtotal: {
    type: Number,
    required: true,
    min: [0, 'Subtotal cannot be negative']
  },
  taxRate: {
    type: Number,
    default: 0.21, // IVA 21% in Spain
    min: 0,
    max: 1
  },
  taxAmount: {
    type: Number,
    required: true,
    min: [0, 'Tax amount cannot be negative']
  },
  shippingCost: {
    type: Number,
    default: 0,
    min: [0, 'Shipping cost cannot be negative']
  },
  totalAmount: {
    type: Number,
    required: true,
    min: [0, 'Total amount cannot be negative']
  },
  status: {
    type: String,
    enum: ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'],
    default: 'pending',
    index: true
  },
  paymentStatus: {
    type: String,
    enum: ['pending', 'paid', 'failed', 'refunded'],
    default: 'pending',
    index: true
  },
  paymentMethod: {
    type: String,
    enum: ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash_on_delivery'],
    required: false
  },
  paymentTransactionId: {
    type: String,
    trim: true
  },
  shippingAddress: {
    type: shippingAddressSchema,
    required: true
  },
  billingAddress: {
    type: shippingAddressSchema,
    required: false
  },
  trackingNumber: {
    type: String,
    trim: true
  },
  shippingCarrier: {
    type: String,
    trim: true
  },
  notes: {
    type: String,
    trim: true,
    maxlength: [500, 'Notes cannot exceed 500 characters']
  },
  statusHistory: [{
    status: {
      type: String,
      required: true
    },
    timestamp: {
      type: Date,
      default: Date.now
    },
    notes: {
      type: String,
      trim: true
    },
    updatedBy: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'User'
    }
  }],
  cancellationReason: {
    type: String,
    trim: true
  },
  refundAmount: {
    type: Number,
    min: [0, 'Refund amount cannot be negative']
  },
  estimatedDeliveryDate: {
    type: Date
  },
  actualDeliveryDate: {
    type: Date
  },
  createdAt: {
    type: Date,
    default: Date.now,
    index: true
  },
  updatedAt: {
    type: Date,
    default: Date.now
  }
}, {
  timestamps: true
});

// Indexes for efficient queries
orderSchema.index({ orderNumber: 1 });
orderSchema.index({ user: 1, createdAt: -1 });
orderSchema.index({ customerEmail: 1 });
orderSchema.index({ status: 1, paymentStatus: 1 });
orderSchema.index({ createdAt: -1 });

// Pre-save middleware to generate order number
orderSchema.pre('save', async function(next) {
  if (!this.orderNumber && this.isNew) {
    // Generate order number: ORD-YYYYMMDD-XXXXX
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 99999).toString().padStart(5, '0');
    this.orderNumber = `ORD-${year}${month}${day}-${random}`;
  }
  next();
});

// Pre-save middleware to calculate totals
orderSchema.pre('save', function(next) {
  if (this.isModified('items') || this.isModified('taxRate') || this.isModified('shippingCost')) {
    // Calculate subtotal from items
    this.subtotal = this.items.reduce((sum, item) => sum + item.subtotal, 0);
    
    // Calculate tax
    this.taxAmount = Math.round(this.subtotal * this.taxRate * 100) / 100;
    
    // Calculate total
    this.totalAmount = Math.round((this.subtotal + this.taxAmount + this.shippingCost) * 100) / 100;
  }
  next();
});

// Pre-save middleware to add status to history
orderSchema.pre('save', function(next) {
  if (this.isModified('status') && !this.isNew) {
    this.statusHistory.push({
      status: this.status,
      timestamp: new Date()
    });
  }
  next();
});

// Method to update order status
orderSchema.methods.updateStatus = async function(newStatus, notes, updatedBy) {
  this.status = newStatus;
  this.statusHistory.push({
    status: newStatus,
    timestamp: new Date(),
    notes: notes,
    updatedBy: updatedBy
  });
  return await this.save();
};

// Method to cancel order
orderSchema.methods.cancel = async function(reason, updatedBy) {
  if (['delivered', 'cancelled', 'refunded'].includes(this.status)) {
    throw new Error(`Cannot cancel order with status: ${this.status}`);
  }
  this.status = 'cancelled';
  this.cancellationReason = reason;
  this.statusHistory.push({
    status: 'cancelled',
    timestamp: new Date(),
    notes: `Cancelled: ${reason}`,
    updatedBy: updatedBy
  });
  return await this.save();
};

// Method to mark as paid
orderSchema.methods.markAsPaid = async function(transactionId, paymentMethod) {
  this.paymentStatus = 'paid';
  this.paymentTransactionId = transactionId;
  this.paymentMethod = paymentMethod;
  return await this.save();
};

// Virtual for checking if order is editable
orderSchema.virtual('isEditable').get(function() {
  return ['pending', 'confirmed'].includes(this.status);
});

// Virtual for checking if order is cancellable
orderSchema.virtual('isCancellable').get(function() {
  return ['pending', 'confirmed', 'processing'].includes(this.status);
});

// Ensure virtuals are included when converting to JSON
orderSchema.set('toJSON', { 
  virtuals: true,
  transform: function(doc, ret) {
    delete ret.__v;
    return ret;
  }
});

const Order = mongoose.model('Order', orderSchema);

module.exports = Order;
