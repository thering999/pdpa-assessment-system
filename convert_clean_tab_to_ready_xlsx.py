import pandas as pd

# กำหนดชื่อไฟล์และชื่อชีท
excel_file = 'CII_SelyAssessment.xlsx'
sheet = 'Clean'

# อ่านข้อมูลจากชีท Clean
# สมมติว่าคอลัมน์ใน Clean คือ: ลำดับที่, รายการ (Objective), ที่มา (Requirement), ส่วนประกอบ, หลักฐาน (Evident), กลุ่ม
cols = ['ลำดับที่', 'รายการ (Objective)', 'ที่มา (Requirement)', 'ส่วนประกอบ', 'หลักฐาน (Evident)', 'กลุ่ม']
df = pd.read_excel(excel_file, sheet_name=sheet, usecols=cols)

# สร้างคอลัมน์ใหม่ให้ตรงกับระบบ
# category_code: กลุ่ม (D1/D2/D3)
df['category_code'] = df['กลุ่ม']
# category_name: "หมวดหมู่ " + กลุ่ม
df['category_name'] = 'หมวดหมู่ ' + df['กลุ่ม']
# category_weight: 1 (หรือจะใส่สูตรอื่นก็ได้)
df['category_weight'] = 1
# category_description: ว่าง
# question_code: กลุ่ม + '-' + ลำดับที่ (เช่น D1-1)
df['question_code'] = df['กลุ่ม'] + '-' + df['ลำดับที่'].astype(str)
# question_text: รายการ (Objective)
df['question_text'] = df['รายการ (Objective)']
# question_weight: 1 (หรือจะใส่สูตรอื่นก็ได้)
df['question_weight'] = 1

# จัดลำดับคอลัมน์ตามระบบ
out_cols = ['category_code','category_name','category_weight','category_description','question_code','question_text','question_weight']
df['category_description'] = ''
df_out = df[out_cols]

# บันทึกเป็นไฟล์ใหม่
out_file = 'CII_SelyAssessment_ready.xlsx'
df_out.to_excel(out_file, index=False)
print(f'สร้างไฟล์ {out_file} เรียบร้อย พร้อมนำเข้าในระบบ')
