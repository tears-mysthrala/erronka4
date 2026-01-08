FROM node:18-alpine AS base

WORKDIR /app

FROM base AS deps
COPY "Zabala Gailetak/package*.json" ./
RUN npm ci --only=production && \
    npm cache clean --force

FROM base AS builder
COPY "Zabala Gailetak/package*.json" ./
RUN npm ci

COPY "Zabala Gailetak/src" ./src

RUN npm run lint

FROM base AS runner
WORKDIR /app

ENV NODE_ENV=production

RUN addgroup --system --gid 1001 nodejs && \
    adduser --system --uid 1001 nodejs

COPY --from=builder --chown=nodejs:nodejs /app/package*.json ./
COPY --from=deps --chown=nodejs:nodejs /app/node_modules ./node_modules
COPY --from=builder --chown=nodejs:nodejs /app/src ./src

USER nodejs

EXPOSE 3000

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
  CMD node -e "require('http').get('http://localhost:3000/api/health', (r) => {process.exit(r.statusCode === 200 ? 0 : 1)})"

CMD ["node", "src/api/app.js"]