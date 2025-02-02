import zipfile
import os

def zip_directory(source_dir, output_zip):
    if not os.path.exists(source_dir):
        print(f"The directory {source_dir} does not exist.")
        return

    with zipfile.ZipFile(output_zip, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(source_dir):
            for file in files:
                file_path = os.path.join(root, file)
                # Add file to zip, preserving directory structure
                zipf.write(file_path, os.path.relpath(file_path, source_dir))

    print(f"Directory {source_dir} successfully zipped into {output_zip}.")

source_directory = r"E:\letoltes\wordpress\foldestheme"
output_zip_file = r"E:\letoltes\wordpress\foldestheme.zip"

zip_directory(source_directory, output_zip_file)
