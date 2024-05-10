# Flexhub frontend

## Code generation
[Flexhub.api.ts](src/Flexhub.api.ts) is generated with [sta](https://github.com/acacode/swagger-typescript-api).
Do not change anything inside the file.  
Use
`npx sta -t flexhub-api-template -p http://nginx/api/doc.json -o ./src -n Flexhub.api.ts`
to generate new version of the file whenever you change API contracts on the backend.
