import os
root = r'c:\Users\Pranith\Downloads\salonease\salonease'
total_files = 0
total_size = 0
for dp, _, files in os.walk(root):
    for f in files:
        try:
            p = os.path.join(dp, f)
            total_files += 1
            total_size += os.path.getsize(p)
        except OSError:
            pass
print(total_files, total_size)
