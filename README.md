# HỆ THỐNG WEB BẢO TÀNG BÙA CHÚ VIỆT NAM
# ĐỒ ÁN THỰC TẬP
import webbrowser
import threading
import time
import uvicorn
from fastapi import FastAPI, Depends, HTTPException, Form
from fastapi.responses import HTMLResponse, JSONResponse, RedirectResponse
from sqlalchemy import create_engine, Column, Integer, String
from sqlalchemy.orm import sessionmaker, declarative_base, Session

# Kết nối cơ sở dữ liệu
DATABASE_URL = "sqlite:///./spells.db"
engine = create_engine(DATABASE_URL, connect_args={"check_same_thread": False})
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

# Định nghĩa bảng dữ liệu
class Spell(Base):
    __tablename__ = "spells"
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String, index=True)
    description = Column(String)

Base.metadata.create_all(bind=engine)

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

app = FastAPI()

# Giao diện trang chủ
@app.get("/", response_class=HTMLResponse)
def home(db: Session = Depends(get_db)):
    spells = db.query(Spell).all()
    html_content = """
    <html>
        <head>
            <title>Bảo Tàng Bùa Chú</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        </head>
        <body class="container mt-5">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/">Bảo Tàng Bùa Chú</a>
            </nav>
            <h1 class="text-center mt-4">Danh sách Bùa Chú</h1>
            <a href="/add" class="btn btn-primary mb-3">Thêm Bùa Chú</a>
            <ul class="list-group">
    """
    for spell in spells:
        html_content += f'''
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{spell.name}</strong>: {spell.description}</span>
                <div>
                    <a href="/spell/{spell.id}" class="btn btn-info btn-sm">Xem</a>
                    <a href="/edit/{spell.id}" class="btn btn-warning btn-sm">Sửa</a>
                    <button onclick="deleteSpell({spell.id})" class="btn btn-danger btn-sm">Xóa</button>
                </div>
            </li>
        '''
    html_content += """
            </ul>
            <script>
                function deleteSpell(id) {
                    if (confirm("Bạn có chắc muốn xóa bùa chú này không?")) {
                        fetch(`/spell/${id}`, { method: 'DELETE' })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                location.reload();
                            });
                    }
                }
            </script>
        </body>
    </html>
    """
    return HTMLResponse(content=html_content)

# API lấy danh sách bùa chú
@app.get("/spells", response_class=JSONResponse)
def get_spells(db: Session = Depends(get_db)):
    spells = db.query(Spell).all()
    return JSONResponse(content={"data": [{"id": s.id, "name": s.name, "description": s.description} for s in spells]})

# API lấy chi tiết bùa chú
@app.get("/spell/{spell_id}", response_class=HTMLResponse)
def get_spell(spell_id: int, db: Session = Depends(get_db)):
    spell = db.query(Spell).filter(Spell.id == spell_id).first()
    if spell:
        return f'<h1>{spell.name}</h1><p>{spell.description}</p><a href="/">Quay lại</a>'
    return HTMLResponse(content="<h1>Không tìm thấy bùa chú</h1><a href='/'>Quay lại</a>", status_code=404)

# Giao diện thêm bùa chú
@app.get("/add", response_class=HTMLResponse)
def add_spell_form():
    return HTMLResponse(content="""
        <html>
            <head>
                <title>Thêm Bùa Chú</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            </head>
            <body class="container mt-5">
                <h1>Thêm Bùa Chú</h1>
                <form method="post" action="/spell">
                    <input type="text" name="name" placeholder="Tên Bùa Chú" class="form-control mb-2" required>
                    <textarea name="description" placeholder="Mô tả" class="form-control mb-2" required></textarea>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </form>
                <a href="/" class="btn btn-secondary mt-3">Quay lại</a>
            </body>
        </html>
    """)

# API thêm bùa chú
@app.post("/spell")
def create_spell(name: str = Form(...), description: str = Form(...), db: Session = Depends(get_db)):
    spell = Spell(name=name, description=description)
    db.add(spell)
    db.commit()
    db.refresh(spell)
    return RedirectResponse(url="/", status_code=303)

# API xóa bùa chú
@app.delete("/spell/{spell_id}")
def delete_spell(spell_id: int, db: Session = Depends(get_db)):
    spell = db.query(Spell).filter(Spell.id == spell_id).first()
    if not spell:
        raise HTTPException(status_code=404, detail="Không tìm thấy bùa chú")
    db.delete(spell)
    db.commit()
    return {"message": "Bùa chú đã bị xóa"}

# Giao diện sửa bùa chú
@app.get("/edit/{spell_id}", response_class=HTMLResponse)
def edit_spell_form(spell_id: int, db: Session = Depends(get_db)):
    spell = db.query(Spell).filter(Spell.id == spell_id).first()
    if not spell:
        return HTMLResponse(content="<h1>Không tìm thấy bùa chú</h1><a href='/'>Quay lại</a>", status_code=404)
    
    return HTMLResponse(content=f"""
        <html>
            <head>
                <title>Sửa Bùa Chú</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            </head>
            <body class="container mt-5">
                <h1>Sửa Bùa Chú</h1>
                <form method="post" action="/update/{spell.id}">
                    <input type="text" name="name" value="{spell.name}" class="form-control mb-2" required>
                    <textarea name="description" class="form-control mb-2" required>{spell.description}</textarea>
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                </form>
                <a href="/" class="btn btn-secondary mt-3">Quay lại</a>
            </body>
        </html>
    """)

@app.post("/update/{spell_id}")
def update_spell(spell_id: int, name: str = Form(...), description: str = Form(...), db: Session = Depends(get_db)):
    spell = db.query(Spell).filter(Spell.id == spell_id).first()
    if not spell:
        raise HTTPException(status_code=404, detail="Không tìm thấy bùa chú")
    
    spell.name = name
    spell.description = description
    db.commit()
    return RedirectResponse(url="/", status_code=303)

# Hàm mở trình duyệt tự động
def open_browser():
    time.sleep(1)  
    webbrowser.open("http://127.0.0.1:8080")

if __name__ == "__main__":
    threading.Thread(target=open_browser).start()  
    uvicorn.run(app, host="127.0.0.1", port=8080)

                                                ------FastAPI----------
from fastapi import FastAPI
import uvicorn
import webbrowser
import threading
import time


app = FastAPI()

@app.get("/")
def home():
    return {"message": "Chào mừng đến với Bảo Tàng Bùa Chú Việt Nam!"}
def open_browser():
    time.sleep(1)  # Chờ server khởi động xong
    webbrowser.open("http://127.0.0.1:8080")  # Mở trình duyệt vào địa chỉ server

if __name__ == "__main__":
    threading.Thread(target=open_browser).start()  # Chạy open_browser() trong luồng riêng
    uvicorn.run(app, host="127.0.0.1", port=8080)

