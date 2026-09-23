# Jewelry Domain Rules: Units, Purity & Karigor Jobs

## 1. Traditional Units Conversion Table
- 1 ভরি (Bhori) = 16 আনা (Ana) [Ana: 0–15]
- 1 আনা (Ana) = 6 রতি (Roti) [Roti: 0–5]
- 1 রতি (Roti) = 10 পয়েন্ট (Point) [Point: 0–9]
- 1 ভরি = 11.664 গ্রাম (Gram)

## 2. Karat Purity Formulas
Pure 24K gold (পাকা সোনা) calculation formula:
$$\text{Pure Gold (পাকা সোনা)} = \text{Gross Weight (মোট ওজন)} \times \frac{\text{Karat}}{24}$$
$$\text{Alloy Metal (খাদ)} = \text{Gross Weight} - \text{Pure Gold}$$

### Purity Ratios:
- **24K Gold**: $\frac{24}{24} = 100\%$ ($1.0000$)
- **22K Gold**: $\frac{22}{24} \approx 91.67\%$ ($0.91667$) $\rightarrow$ ১ ভরি = ১০.৬৯২ গ্রাম পাকা সোনা, ০.৯৭২ গ্রাম খাদ
- **21K Gold**: $\frac{21}{24} = 87.50\%$ ($0.8750$) $\rightarrow$ ১ ভরি = ১০.২০৬ গ্রাম পাকা সোনা, ১.৪৫৮ গ্রাম খাদ
- **18K Gold**: $\frac{18}{24} = 75.00\%$ ($0.7500$) $\rightarrow$ ১ ভরি = ৮.৭৪৮ গ্রাম পাকা সোনা, ২.৯১৬ গ্রাম খাদ
- **Traditional (সনাতন)**: $\frac{14}{24} \approx 58.33\%$ ($0.58333$) $\rightarrow$ ১ ভরি = ৬.৮০৪ গ্রাম পাকা সোনা, ৪.৮৬০ গ্রাম খাদ

## 3. Karigor Job Mapping Convention
When creating or assigning a job to a Karigor (`karigor_jobs`):
- `given_gross_weight`: Finished jewelry gross target weight (`target_gram`)
- `given_purity_weight`: Pure 24K gold allocated based on karat purity (`raw_gold_needed`)
- `assigned_extra_raw_gold`: Extra raw gold allowance (default: `0.000`)
- `returned_gross_weight` & `returned_raw_gold`: Recorded when job is completed
- `wastage_gold` = $(\text{given\_purity\_weight} + \text{used\_extra\_raw\_gold}) - \text{returned\_raw\_gold}$
