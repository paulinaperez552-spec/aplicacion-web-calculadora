# 💼 Calculadora de Salario Quincenal

Aplicación web en PHP para calcular el salario quincenal de un empleado según las horas trabajadas en distintos turnos, aplicando deducciones variables conforme a escalas salariales definidas.

---

## 📋 Descripción del Proyecto

Esta herramienta permite ingresar los datos de un empleado (nombre, cédula y horas por turno) y obtiene de forma automática:

- **Sueldo Bruto** desglosado por turno (diurno, vespertino y nocturno).
- **Deducciones** de Ahorro Habitacional y Seguro Social según escala salarial.
- **Sueldo Neto Quincenal** listo para presentar en nómina.

### Tarifas por hora vigentes

| Turno       | Tarifa (Bs./h) |
|-------------|---------------:|
| Diurno      |      675,00    |
| Vespertino  |      700,00    |
| Nocturno    |      956,23    |

### Escalas de deducciones

| Sueldo Bruto                    | Ahorro Habitacional | Seguro Social |
|---------------------------------|--------------------:|--------------:|
| Menor a Bs. 85.000              |              0,10 % |        0,15 % |
| Entre Bs. 85.000 y Bs. 150.000  |              0,15 % |        0,20 % |
| Mayor a Bs. 150.000             |              0,30 % |        0,25 % |

---

## ⚙️ Requisitos de Ejecución

Se necesita un servidor local con soporte para **PHP 7.4 o superior**. Las opciones más sencillas son:

| Software | Descarga |
|----------|----------|
| **XAMPP** | https://www.apachefriends.org |
| **WAMP**  | https://www.wampserver.com    |
| **Laragon** | https://laragon.org         |

> **No** se requieren extensiones adicionales de PHP ni bases de datos.

---

## 🗂️ Estructura de Archivos

```
APLICACION PAULINA/
├── index.php     ← Formulario + procesamiento PHP + diseño CSS
└── README.md     ← Este archivo
```

> Toda la lógica (formulario, validaciones, cálculos y presentación de resultados)
> está integrada en un único archivo `index.php` usando el método `POST`.

---

## 🚀 Instrucciones de Uso

### 1. Copiar los archivos al servidor

Coloca la carpeta `APLICACION PAULINA/` dentro del directorio raíz de tu servidor:

| Servidor | Directorio raíz |
|----------|----------------|
| XAMPP    | `C:\xampp\htdocs\` |
| WAMP     | `C:\wamp64\www\` |
| Laragon  | `C:\laragon\www\` |

### 2. Iniciar el servidor

- **XAMPP / WAMP**: Abre el panel de control y haz clic en **Start** junto a *Apache*.
- **Laragon**: Haz doble clic en el ejecutable y selecciona *Start All*.

### 3. Abrir la aplicación en el navegador

```
http://localhost/APLICACION PAULINA/index.php
```

> Si hay espacios en el nombre de carpeta, usa `%20`:
> `http://localhost/APLICACION%20PAULINA/index.php`

### 4. Completar el formulario

1. Ingresa el **nombre completo** y la **cédula** del empleado.
2. Registra las **horas trabajadas** en cada turno (diurno, vespertino, nocturno).  
   - Puedes ingresar valores decimales en pasos de 0,5 (media hora).
   - Deja en `0` los turnos no trabajados.
3. Haz clic en **"Calcular Salario Quincenal"**.

### 5. Interpretar los resultados

La aplicación mostrará:
- Desglose de ingresos por turno.
- Escala salarial aplicada con los porcentajes de deducción.
- Tabla de deducciones detallada.
- **Sueldo Neto Quincenal** resaltado.

Para calcular otro empleado, haz clic en **"Calcular otro empleado"**.

---

## 🛡️ Validaciones Incluidas

- Nombre y cédula obligatorios.
- Cédula debe contener entre 6 y 9 dígitos numéricos.
- Las horas deben ser números mayores o iguales a cero.
- Los errores se muestran en pantalla sin perder los datos ingresados.

---

*Desarrollado con PHP · Bootstrap 5 · Bootstrap Icons*
