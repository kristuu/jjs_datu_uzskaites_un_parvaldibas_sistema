const { defineConfig } = require('@vue/cli-service')
const Components = require('unplugin-vue-components/webpack').default;
const { PrimeVueResolver } = require('unplugin-vue-components/resolvers');

module.exports = defineConfig({
  transpileDependencies: true,
  configureWebpack: {
    plugins: [
      Components({
        resolvers: [PrimeVueResolver()],
      })
    ],
  },
})