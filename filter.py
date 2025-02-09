import pandas as pd

# Load the Excel file
file_path = "FFG_versenyek_osszegyujtott.xlsx"
sheet_name = "Eredmények"

# Load the sheet into a DataFrame
df = pd.read_excel(file_path, sheet_name=sheet_name)

# Select columns D to I by their index (D=3, I=8)
subset = df.iloc[:, 3:9]

# Create a mask that checks if cells contain actual values
# Exclude cells with "rv" or "r.v." and NaN values
def has_valid_value(x):
    if pd.isna(x):
        return False
    if isinstance(x, str) and x.lower().replace('.', '') in ['rv', 'r v']:
        return False
    return True

# Apply the mask to check for valid values in any column
valid_rows_mask = subset.applymap(has_valid_value).any(axis=1)

# Filter the original DataFrame using the mask
filtered_df = df[valid_rows_mask]

# Save the filtered DataFrame to CSV with UTF-8 encoding
output_path = "filtered_" + file_path.replace('.xlsx', '.csv')
filtered_df.to_csv(output_path, index=False, encoding='utf-8')

print(f"Filtered CSV file saved as '{output_path}' with UTF-8 encoding")