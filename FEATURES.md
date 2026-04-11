## Event Records

* Event records — custom TCA record type (`tx_maievents_event`) with date, location, categories, and image
* Registration records — event registration TCA (`tx_maievents_registration`) with attendee data and waiting-list support

## Calendar Views

* Month view — full monthly grid with event indicators
* Week view — seven-day view with event display
* List view — chronological event list with configurable limit

## Export & Integration

* iCal export — export events as RFC 5545-compliant `.ics` files via `EventsController`
* EventProviderInterface — pluggable data source aggregation; implement `Maispace\MaiEvents\EventProvider\EventProviderInterface` to contribute events from other extensions
* EventsDataProcessor — builds the calendar grid and navigation for Fluid templates
* FlexForm settings — view mode (month / week / list) and list limit configurable per content element

## Event Registration

* Registration flow — Browse events → Register → Confirm via email dispatched via `mai_mail`
* Attendee list — per-event attendee management with CSV export in the backend
* Waiting list — optional waiting-list support via `tx_maievents_registration.waiting_list` flag
