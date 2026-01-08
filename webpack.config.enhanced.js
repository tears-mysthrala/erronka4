const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const { SubresourceIntegrityPlugin } = require('webpack-subresource-integrity');
const CompressionPlugin = require('compression-webpack-plugin');

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';
  const isDevelopment = !isProduction;

  return {
    mode: isProduction ? 'production' : 'development',
    entry: {
      main: './Zabala Gailetak/src/web/app/index.js'
    },
    output: {
      path: path.resolve(__dirname, 'dist/web'),
      filename: isProduction ? 'js/[name].[contenthash:8].js' : 'js/[name].js',
      chunkFilename: isProduction ? 'js/[name].[contenthash:8].chunk.js' : 'js/[name].chunk.js',
      assetModuleFilename: 'assets/[name].[contenthash:8][ext]',
      clean: true,
      publicPath: '/',
      crossOriginLoading: 'anonymous' // Required for SRI
    },
    
    module: {
      rules: [
        // JavaScript/JSX
        {
          test: /\.(js|jsx)$/,
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: {
              cacheDirectory: true,
              cacheCompression: false,
              presets: [
                ['@babel/preset-env', {
                  useBuiltIns: 'usage',
                  corejs: 3,
                  modules: false
                }],
                ['@babel/preset-react', {
                  runtime: 'automatic'
                }]
              ],
              plugins: [
                isDevelopment && 'react-refresh/babel'
              ].filter(Boolean)
            }
          }
        },
        
        // CSS
        {
          test: /\.css$/,
          use: [
            isProduction ? MiniCssExtractPlugin.loader : 'style-loader',
            {
              loader: 'css-loader',
              options: {
                importLoaders: 1,
                sourceMap: !isProduction,
                modules: {
                  auto: true,
                  localIdentName: isProduction 
                    ? '[hash:base64:8]' 
                    : '[name]__[local]___[hash:base64:5]'
                }
              }
            },
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  plugins: [
                    'autoprefixer',
                    isProduction && 'cssnano'
                  ].filter(Boolean)
                }
              }
            }
          ]
        },
        
        // Images
        {
          test: /\.(png|svg|jpg|jpeg|gif|webp|ico)$/i,
          type: 'asset',
          parser: {
            dataUrlCondition: {
              maxSize: 10 * 1024 // 10kb
            }
          },
          generator: {
            filename: 'images/[name].[contenthash:8][ext]'
          }
        },
        
        // Fonts
        {
          test: /\.(woff|woff2|eot|ttf|otf)$/i,
          type: 'asset/resource',
          generator: {
            filename: 'fonts/[name].[contenthash:8][ext]'
          }
        }
      ]
    },
    
    resolve: {
      extensions: ['.js', '.jsx', '.json'],
      alias: {
        '@': path.resolve(__dirname, 'Zabala Gailetak/src/web/app'),
        '@components': path.resolve(__dirname, 'Zabala Gailetak/src/web/app/components'),
        '@pages': path.resolve(__dirname, 'Zabala Gailetak/src/web/app/pages'),
        '@services': path.resolve(__dirname, 'Zabala Gailetak/src/web/app/services'),
        '@context': path.resolve(__dirname, 'Zabala Gailetak/src/web/app/context'),
        '@styles': path.resolve(__dirname, 'Zabala Gailetak/src/web/app/styles')
      }
    },
    
    plugins: [
      // HTML generation with security features
      new HtmlWebpackPlugin({
        template: './Zabala Gailetak/src/web/index.html',
        filename: 'index.html',
        inject: true,
        minify: isProduction ? {
          removeComments: true,
          collapseWhitespace: true,
          removeRedundantAttributes: true,
          useShortDoctype: true,
          removeEmptyAttributes: true,
          removeStyleLinkTypeAttributes: true,
          keepClosingSlash: true,
          minifyJS: true,
          minifyCSS: true,
          minifyURLs: true
        } : false,
        meta: {
          'viewport': 'width=device-width, initial-scale=1.0, shrink-to-fit=no',
          'description': 'Zabala Gailetak - Sistema Seguru eta Aurreratua',
          'theme-color': '#4A90E2',
          'X-UA-Compatible': { 'http-equiv': 'X-UA-Compatible', content: 'IE=edge' }
        },
        scriptLoading: 'defer'
      }),
      
      // Extract CSS to separate files in production
      isProduction && new MiniCssExtractPlugin({
        filename: 'css/[name].[contenthash:8].css',
        chunkFilename: 'css/[name].[contenthash:8].chunk.css',
        ignoreOrder: false
      }),
      
      // Subresource Integrity for security
      isProduction && new SubresourceIntegrityPlugin({
        hashFuncNames: ['sha256', 'sha384'],
        enabled: true
      }),
      
      // Compression for production
      isProduction && new CompressionPlugin({
        filename: '[path][base].gz',
        algorithm: 'gzip',
        test: /\.(js|css|html|svg)$/,
        threshold: 10240, // 10kb
        minRatio: 0.8
      }),
      
      // Brotli compression
      isProduction && new CompressionPlugin({
        filename: '[path][base].br',
        algorithm: 'brotliCompress',
        test: /\.(js|css|html|svg)$/,
        threshold: 10240,
        minRatio: 0.8
      }),
      
      // Bundle analyzer in production
      isProduction && process.env.ANALYZE && new BundleAnalyzerPlugin({
        analyzerMode: 'static',
        openAnalyzer: false,
        reportFilename: '../reports/bundle-analysis.html'
      })
    ].filter(Boolean),
    
    optimization: {
      minimize: isProduction,
      minimizer: [
        new TerserPlugin({
          terserOptions: {
            parse: {
              ecma: 2020
            },
            compress: {
              ecma: 5,
              warnings: false,
              comparisons: false,
              inline: 2,
              drop_console: isProduction,
              drop_debugger: isProduction
            },
            mangle: {
              safari10: true
            },
            output: {
              ecma: 5,
              comments: false,
              ascii_only: true
            }
          }
        }),
        new CssMinimizerPlugin()
      ],
      splitChunks: {
        chunks: 'all',
        maxInitialRequests: 25,
        minSize: 20000,
        cacheGroups: {
          // React vendor bundle
          react: {
            test: /[\\/]node_modules[\\/](react|react-dom|react-router-dom)[\\/]/,
            name: 'react-vendor',
            priority: 40,
            reuseExistingChunk: true
          },
          // Other vendor libraries
          vendor: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendors',
            priority: 20,
            reuseExistingChunk: true
          },
          // Common code used across multiple chunks
          common: {
            name: 'common',
            minChunks: 2,
            priority: 10,
            reuseExistingChunk: true,
            enforce: true
          }
        }
      },
      runtimeChunk: {
        name: entrypoint => `runtime-${entrypoint.name}`
      },
      moduleIds: 'deterministic',
      usedExports: true,
      sideEffects: true
    },
    
    performance: {
      hints: isProduction ? 'warning' : false,
      maxEntrypointSize: 512000, // 500kb
      maxAssetSize: 512000,
      assetFilter: function(assetFilename) {
        return !/\.map$/.test(assetFilename) && !/\.(woff|woff2|ttf|eot)$/.test(assetFilename);
      }
    },
    
    devServer: {
      static: {
        directory: path.join(__dirname, 'public'),
        publicPath: '/'
      },
      compress: true,
      port: 3001,
      hot: true,
      historyApiFallback: true,
      open: false,
      client: {
        overlay: {
          errors: true,
          warnings: false
        },
        progress: true
      },
      proxy: {
        '/api': {
          target: 'http://localhost:3000',
          changeOrigin: true,
          secure: false
        }
      },
      headers: {
        'X-Content-Type-Options': 'nosniff',
        'X-Frame-Options': 'DENY',
        'X-XSS-Protection': '1; mode=block',
        'Referrer-Policy': 'strict-origin-when-cross-origin',
        'Content-Security-Policy': [
          "default-src 'self'",
          "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
          "style-src 'self' 'unsafe-inline'",
          "img-src 'self' data: https:",
          "font-src 'self' data:",
          "connect-src 'self' http://localhost:3000 ws://localhost:3001"
        ].join('; ')
      }
    },
    
    devtool: isProduction 
      ? 'source-map' 
      : 'eval-cheap-module-source-map',
    
    stats: {
      colors: true,
      hash: true,
      timings: true,
      assets: true,
      chunks: false,
      chunkModules: false,
      modules: false,
      children: false,
      cached: false,
      reasons: false,
      source: false,
      errorDetails: true,
      warnings: true
    },
    
    cache: {
      type: 'filesystem',
      buildDependencies: {
        config: [__filename]
      }
    }
  };
};
