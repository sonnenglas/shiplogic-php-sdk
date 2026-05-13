# Changelog

## 0.1.0 - 2026-05-13

Initial release.

- Bearer-token authenticated client targeting `api.shiplogic.com` (production) and `sandbox.shiplogic.com` (sandbox).
- `ShipmentService` covering shipment creation, retrieval, label download, sticker label, and cancellation.
- `RatesService` covering rate quotes.
- `TrackingService` covering tracking events.
- Webhook payload DTOs for tracking events, shipment notes, parcel dimension changes, and address changes.
