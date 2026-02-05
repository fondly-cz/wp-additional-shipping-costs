**Česky** | [English](README.md)

# Dodatečné náklady na dopravu pro WooCommerce

Flexibilní plugin pro WooCommerce, který umožňuje přidat specifické dodatečné náklady k cenám dopravy na základě **Přepravních tříd** a **Způsobů dopravy**.

Tento plugin je užitečný zejména v případech, kdy potřebujete účtovat příplatky za těžké položky, křehké zboží nebo specifickou manipulaci, která se liší podle dopravce (např. příplatek za těžkou položku pouze u konkrétního kurýra).

## Funkce

- **Matice nákladů**: Přehledná tabulka pro nastavení příplatků pro každou kombinaci Přepravní třídy a Způsobu dopravy.
- **Výpočet za kus**: Možnost účtovat příplatek jednorázově za třídu nebo **za kus** (vynásobeno počtem položek v dané třídě).
- **Zpracování DPH**: Možnost zadávat ceny včetně nebo bez DPH. Plugin se postará o zpětný výpočet tak, aby konečná cena odpovídala.
- **Vysoká kompatibilita**: Běží s vysokou prioritou, aby bylo zajištěno přičtení nákladů i v případě, že jiné přepravní pluginy (jako např. PPL CZ) resetují sazby během výpočtu.

## Kompatibilita

Plugin byl testován a je plně kompatibilní s:
- [PPL CZ WooCommerce](https://github.com/PPL-CZ/PPL-WooCommerce)
- [Zásilkovna WooCommerce](https://github.com/Zasilkovna/WooCommerce)

## Požadavky

- WordPress
- WooCommerce

## Konfigurace

1. Přejděte do **WooCommerce > Additional Shipping Costs** (nebo hledejte "Additional Shipping Costs" v admin menu).
2. Uvidíte matici, kde řádky představují vaše **Způsoby dopravy** a sloupce vaše **Přepravní třídy**.
3. **Zadání nákladů**: Zadejte dodatečnou částku pro každou kombinaci (Doprava + Třída).
4. **Za kus (Add per piece)**: Ve spodním řádku "Options" můžete pro každý sloupec přepravní třídy zaškrtnout "Add per piece". Pokud je zaškrtnuto, cena se vynásobí počtem položek v dané třídě.
5. **Nastavení daně**: Zaškrtněte "The prices entered below include tax", pokud zadáváte hrubé ceny s DPH. Plugin vypočítá základ daně podle standardních sazeb DPH pro dopravu.
6. Klikněte na **Save Settings**.

## Příklady použití

- **Příplatek za nadrozměr**: Máte přepravní třídu "Nadrozměr". Chcete přidat 50 Kč navíc, když zákazník zvolí "Standardní doručení", ale 100 Kč, když zvolí "Expres".
- **Manipulační poplatek**: Účtujete 20 Kč navíc za každou jednotlivou položku v přepravní třídě "Křehké", bez ohledu na zvoleného dopravce.
