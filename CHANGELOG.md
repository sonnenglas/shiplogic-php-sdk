# Changelog

## 1.0.1 - 2026-06-23

- Package author metadata, README credits, and LICENSE copyright now list
  Przemek Peron (`przemek@sonnenglas.net`).

## 1.0.0 - 2026-05-19

First stable release. Verified end-to-end against the live Shiplogic sandbox API (rates, shipment creation, label download, tracking, cancellation).

- Bearer-token authenticated client for the Shiplogic REST API (`api.shiplogic.com`); the sandbox/production environment is determined by the API token.
- `ShipmentService`: shipment creation, retrieval, label and sticker PDF downloads, cancellation.
- `RatesService`: rate quotes.
- `TrackingService`: tracking lookups.
- Webhook payload DTOs for tracking events, shipment notes, parcel dimension changes, and address changes.

### Pre-release history

## 0.1.1 - 2026-05-19

- Fix `Client::URI_SANDBOX`: Shiplogic exposes a single API host (`api.shiplogic.com`) for both sandbox and production; the environment is determined by the API token, not the URL. `sandbox.shiplogic.com` is the web client portal and was returning HTML instead of API responses.
- Fix `TrackingResponseParser`: the `GET /tracking/shipments` endpoint wraps the shipment in a `shipments` array (alongside `tracking_steps`); the parser read `status` / `tracking_events` from the top level and returned empty results. It now unwraps `shipments[0]`, while still supporting the flat shape used by tracking-event webhooks.

## 0.1.0 - 2026-05-13

Initial release.

- Bearer-token authenticated client targeting `api.shiplogic.com` (production) and `sandbox.shiplogic.com` (sandbox).
- `ShipmentService` covering shipment creation, retrieval, label download, sticker label, and cancellation.
- `RatesService` covering rate quotes.
- `TrackingService` covering tracking events.
- Webhook payload DTOs for tracking events, shipment notes, parcel dimension changes, and address changes.
