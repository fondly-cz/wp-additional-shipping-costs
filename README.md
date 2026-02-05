[Česky](README.cs.md) | **English**

# Additional Shipping Costs for WooCommerce

A flexible WooCommerce plugin that allows you to add specific extra costs to your shipping rates based on **Shipping Classes** and **Shipping Methods**. 

This plugin is particularly useful when you need to charge extra for heavy items, fragile goods, or specific logistical handling that varies by carrier (e.g., specific surcharge for a heavy item only when using a specific courier).

## Features

- **Cost Matrix**: Visual grid to set extra costs for every combination of Shipping Class and Shipping Method.
- **Per-Item Calculation**: Option to charge the extra fee once per class or **per item** (multiplied by the quantity of items in that class).
- **Tax Handling**: Option to input prices either including or excluding tax. the plugin handles the backward calculation to ensure the final total matches.
- **High Compatibility**: Runs with high priority to ensure costs are added even if other shipping plugins (like PPL CZ) reset rates during calculation.

## Compatibility

The plugin has been tested and is fully compatible with:
- [PPL CZ WooCommerce](https://github.com/PPL-CZ/PPL-WooCommerce)
- [Packeta (Zásilkovna) WooCommerce](https://github.com/Zasilkovna/WooCommerce)

## Requirements

- WordPress
- WooCommerce

## Configuration

1. Go to **WooCommerce > Additional Shipping Costs** (or look for "Additional Shipping Costs" in the admin menu).
2. You will see a matrix where rows represent your **Shipping Methods** and columns represent your **Shipping Classes**.
3. **Enter Costs**: Input the additional amount you want to charge for each combination (Method + Class).
4. **Per Piece**: The bottom row "Options" allows you to check "Add per piece" for each shipping class column. If checked, the cost will be multiplied by the number of items in that class.
5. **Tax Setting**: Check "The prices entered below include tax" if your input values are gross prices. The plugin will calculate the net amount regarding standard shipping tax rates.
6. Click **Save Settings**.

## Example Scenarios

- **Heavy Item Surcharge**: You have a shipping class "Heavy". You want to add $5 extra when the customer chooses "Standard Delivery", but $10 extra if they choose "Express".
- **Handling Fee**: You charge $2 extra for every single item in the "Fragile" shipping class, regardless of the shipping method.