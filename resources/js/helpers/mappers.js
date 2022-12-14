const validateTaxNumberMap = (tax) => {
  tax.CLAVE_Y_VALOR_CATASTRAL = parseInt(tax.CLAVE_Y_VALOR_CATASTRAL)
    ? `${parseInt(tax.CLAVE_Y_VALOR_CATASTRAL)}`
    : 'S/N'
  tax.NO_ADEUDO_PREDIAL = parseInt(tax.NO_ADEUDO_PREDIAL)
    ? `${parseInt(tax.NO_ADEUDO_PREDIAL)}`
    : 'S/N'
  tax.APORTACIONES_MEJORAS = parseInt(tax.APORTACIONES_MEJORAS)
    ? `${parseInt(tax.APORTACIONES_MEJORAS)}`
    : 'S/N'
  tax.created_in_year = new Date(tax.created_at).getFullYear() || ''

  return tax
}

const mapTaxesData = (taxes) => taxes.map((tax) => {
  return validateTaxNumberMap(tax)
})

export { validateTaxNumberMap, mapTaxesData }
