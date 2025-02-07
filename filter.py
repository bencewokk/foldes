import pandas as pd

# Load the Excel file
file_path = "FFG_versenyek_osszegyujtott.xlsx"  # Change this to your Excel file path
sheet_name = "Eredmények"  # Change this to your sheet name if needed

# Load the sheet into a DataFrame
df = pd.read_excel(file_path, sheet_name=sheet_name)

# Select columns D to I by their index (D=3, I=8) since DataFrame columns are zero-indexed
subset = df.iloc[:, 3:9]

# Keep only rows where any value exists in columns D to I
filtered_df = df[subset.notna().any(axis=1)]

# Save the filtered DataFrame back to Excel (encoding argument removed)
output_path = "filtered_" + file_path
with pd.ExcelWriter(output_path, engine='openpyxl') as writer:
    filtered_df.to_excel(writer, index=False)

print(f"Filtered Excel file saved as '{output_path}'")
