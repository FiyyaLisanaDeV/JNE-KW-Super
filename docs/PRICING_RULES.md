# Tarif

Ongkir dihitung oleh `App\Services\ShippingCostCalculator`.

Input utama:

- `route_id`
- `package_category`
- `actual_weight`
- `length_cm`, `width_cm`, `height_cm`
- `pickup_selected`, `delivery_selected`, `packing_selected`, `handling_selected`
- `discount_amount`

Formula:

```text
volume_weight = length_cm * width_cm * height_cm / volume_divisor
chargeable_weight = max(actual_weight, volume_weight, minimum_weight)
total =
  base_price
  + max(0, chargeable_weight - minimum_weight) * price_per_kg
  + selected fees
  - discount_amount
```

Rule penting:

- Tarif selalu dibaca dari `pricing_rules`.
- Tarif harus aktif.
- Total minimum adalah `0`.
- Missing dimensions menghasilkan `volume_weight = 0`.
- Breakdown dikembalikan untuk preview UI dan snapshot transaksi shipment.
