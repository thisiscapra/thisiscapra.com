"use strict";
const path = require("path");
const webpack = require("webpack");
const isProduction = process.env.NODE_ENV === 'production';
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const CompressionPlugin = require('compression-webpack-plugin');
const cssLoaders = [
  {
    loader: "css-loader",
    options: {
      modules: true,
      minimize: true
    }
  },
  {
    loader: "sass-loader"
  }
]
module.exports = {
  context: __dirname + "/source",
  devtool: isProduction ? false : 'source-map',
  entry: {
    application: './javascripts/application.js',
  },
  resolve: {
    alias: {
      "TweenLite": path.resolve('node_modules', 'gsap/src/minified/TweenLite.min.js'),
      "TweenMax": path.resolve('node_modules', 'gsap/src/minified/TweenMax.min.js'),
      "TimelineLite": path.resolve('node_modules', 'gsap/src/minified/TimelineLite.min.js'),
      "TimelineMax": path.resolve('node_modules', 'gsap/src/minified/TimelineMax.min.js'),
      "SmoothScroll": path.resolve('node_modules', 'smooth-scroll/dist/smooth-scroll.min'),
      "ScrollMagic": path.resolve('node_modules', 'scrollmagic/scrollmagic/minified/ScrollMagic.min.js'),
      "animation.gsap": path.resolve('node_modules', 'scrollmagic/scrollmagic/minified/plugins/animation.gsap.min.js'),
      "debug.addIndicators": path.resolve('node_modules', 'scrollmagic/scrollmagic/minified/plugins/debug.addIndicators.min.js')
    },
  },
  output: {
    path: __dirname + '/.tmp/dist',
    filename: 'javascripts/[name].bundle.js',
  },
  module: {
    rules: [
      {
        test: /source\/javascripts\/.*\.js$/,
        exclude: /node_modules|\.tmp|vendor/,
        loader: 'babel-loader',
        query: {
          presets: ['env'],
        },
      },
      {
        test: /\.(sass|scss)$/,
        use: ExtractTextPlugin.extract({
          fallback: "style-loader",
          use: "css-loader"
        })
      },
    ],//end rules
  },

  optimization: {
    minimize: true,
    minimizer: [
      new UglifyJsPlugin({
        sourceMap: true,
        test: /\.js(\?.*)?$/i,
        uglifyOptions: {
          warnings: false,
          mangle: true
        }
      })
    ]
  },

  plugins: [
    new CompressionPlugin()
  ],
};